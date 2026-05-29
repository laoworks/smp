<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminResourceManager
{
    protected array $cache = [];

    public function all(): array
    {
        return collect(config('admin_resources', []))
            ->map(fn(array $config, string $key) => $this->get($key))
            ->values()
            ->all();
    }

    public function get(string $key): array
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $config = config("admin_resources.{$key}");

        abort_unless($config && isset($config['model']), 404);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = app($config['model']);
        $table = $model->getTable();
        $columns = collect(Schema::getColumns($table))->keyBy('name');
        $foreignKeys = $this->mapForeignKeys($table);
        $uniqueColumns = $this->getUniqueColumns($table);
        $label = $config['label'] ?? Str::headline(class_basename($config['model']));

        $fields = collect($model->getFillable())
            ->map(function (string $field) use ($model, $table, $columns, $foreignKeys, $uniqueColumns) {
                return $this->buildField(
                    $field,
                    $model,
                    $table,
                    $columns->get($field),
                    $foreignKeys[$field] ?? null,
                    $uniqueColumns
                );
            })
            ->all();

        $titleField = $this->detectTitleField($fields);
        $imageField = collect($fields)
            ->first(fn(array $field) => $field['type'] === 'file' && $field['is_image']);

        $tableFields = collect($fields)
            ->reject(fn(array $field) => in_array($field['type'], ['file', 'textarea'], true))
            ->reject(fn(array $field) => $field['name'] === $titleField)
            ->take(3)
            ->values()
            ->all();

        if ($tableFields === []) {
            $tableFields = collect($fields)
                ->reject(fn(array $field) => $field['name'] === $titleField)
                ->take(3)
                ->values()
                ->all();
        }

        return $this->cache[$key] = [
            'key' => $key,
            'label' => $label,
            'model' => $config['model'],
            'table' => $table,
            'route_name' => "admin.{$key}",
            'exportable' => (bool) ($config['exportable'] ?? false),
            'fields' => $fields,
            'title_field' => $titleField,
            'image_field' => $imageField['name'] ?? null,
            'table_fields' => $tableFields,
        ];
    }

    public function findRecord(string $key, int|string $id): Model
    {
        $resource = $this->get($key);
        $modelClass = $resource['model'];

        return $modelClass::query()->findOrFail($id);
    }

    public function buildValidationRules(array $resource, ?Model $record = null): array
    {
        $rules = [];

        foreach ($resource['fields'] as $field) {
            $fieldRules = [];
            $isUpdate = $record !== null;

            if ($field['type'] === 'file') {
                $fieldRules[] = $field['required'] && ! $isUpdate ? 'required' : 'nullable';
                $fieldRules[] = $field['is_image'] ? 'image' : 'file';
                $fieldRules[] = 'max:2048';
            } else {
                $fieldRules[] = $field['required'] ? 'required' : 'nullable';

                switch ($field['type']) {
                    case 'relation':
                        $fieldRules[] = 'integer';
                        $fieldRules[] = "exists:{$field['foreign_table']},{$field['foreign_column']}";
                        break;
                    case 'boolean':
                        $fieldRules[] = 'boolean';
                        break;
                    case 'date':
                    case 'datetime':
                        $fieldRules[] = 'date';
                        break;
                    case 'number':
                        $fieldRules[] = 'integer';
                        if (($field['column_type'] ?? null) === 'year') {
                            $fieldRules[] = 'between:1901,2155';
                        }
                        break;
                    case 'decimal':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'url':
                        $fieldRules[] = 'url';
                        break;
                    case 'color':
                        $fieldRules[] = 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/';
                        break;
                    default:
                        $fieldRules[] = 'string';
                        break;
                }

                if ($field['options'] !== []) {
                    $fieldRules[] = Rule::in(array_column($field['options'], 'value'));
                }
            }

            if ($field['unique']) {
                $rule = Rule::unique($resource['table'], $field['name']);

                if ($record) {
                    $rule->ignore($record->getKey(), $record->getKeyName());
                }

                $fieldRules[] = $rule;
            }

            $rules[$field['name']] = $fieldRules;
        }

        return $rules;
    }

    public function extractPayload(Request $request, array $resource, ?Model $record = null): array
    {
        $validated = $request->validate($this->buildValidationRules($resource, $record));
        $payload = [];

        foreach ($resource['fields'] as $field) {
            $name = $field['name'];

            if ($field['type'] === 'file') {
                if ($request->hasFile($name)) {
                    if ($record && filled($record->{$name})) {
                        $this->deleteStoredFile($record->{$name});
                    }

                    $payload[$name] = $this->storeUploadedFile($request->file($name), $resource['key']);
                }

                continue;
            }

            if ($field['type'] === 'boolean') {
                $payload[$name] = $request->boolean($name);
                continue;
            }

            if (! array_key_exists($name, $validated)) {
                continue;
            }

            $value = $validated[$name] ?? null;

            if ($value === '' && ! $field['required']) {
                $value = null;
            }

            if ($record === null && $value === null && array_key_exists('default', $field) && $field['default'] !== null) {
                continue;
            }

            $payload[$name] = $value;
        }

        return $payload;
    }

    public function deleteFilesForRecord(array $resource, Model $record): void
    {
        foreach ($resource['fields'] as $field) {
            if ($field['type'] === 'file' && filled($record->{$field['name']})) {
                $this->deleteStoredFile($record->{$field['name']});
            }
        }
    }

    public function getTitle(Model $record, array $resource): string
    {
        $titleField = $resource['title_field'];
        $value = $titleField ? data_get($record, $titleField) : null;

        return filled($value) ? (string) $value : 'Data #' . $record->getKey();
    }

    public function getPreviewImageUrl(Model $record, array $resource): ?string
    {
        if (! $resource['image_field']) {
            return null;
        }

        $value = $record->{$resource['image_field']};

        if (! filled($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    public function displayValue(Model $record, array $field): string
    {
        $value = $record->{$field['name']};

        if ($value === null || $value === '') {
            return '-';
        }

        return match ($field['type']) {
            'boolean' => $value ? 'Aktif' : 'Tidak Aktif',
            'relation' => $this->findOptionLabel($field, $value) ?? '#' . $value,
            'date' => optional($record->{$field['name']})->format('d M Y') ?? (string) $value,
            'datetime' => optional($record->{$field['name']})->format('d M Y H:i') ?? (string) $value,
            'file' => basename((string) $value),
            default => Str::limit($field['rich_text'] ? trim(strip_tags((string) $value)) : (string) $value, 80),
        };
    }

    public function isImageField(array $field): bool
    {
        return $field['type'] === 'file' && $field['is_image'];
    }

    public function fieldValue(Model $record, array $field): mixed
    {
        $value = $record->{$field['name']};

        if ($value === null) {
            return null;
        }

        return match ($field['type']) {
            'date' => optional($record->{$field['name']})->format('Y-m-d'),
            'datetime' => optional($record->{$field['name']})->format('Y-m-d\TH:i'),
            default => $value,
        };
    }

    public function findOptionLabel(array $field, mixed $value): ?string
    {
        $match = collect($field['options'])
            ->first(fn(array $option) => (string) $option['value'] === (string) $value);

        return $match['label'] ?? null;
    }

    protected function buildField(
        string $name,
        Model $model,
        string $table,
        ?array $column,
        ?array $foreignKey,
        array $uniqueColumns
    ): array {
        $casts = $model->getCasts();
        $castType = $casts[$name] ?? null;
        $columnType = $column['type_name'] ?? 'string';
        $fullType = $column['type'] ?? $columnType;
        $options = [];
        $type = 'text';
        $isImage = $this->looksLikeImageField($name);

        if ($foreignKey) {
            $type = 'relation';
            $options = $this->getRelationOptions($foreignKey);
        } elseif ($enumOptions = $this->parseEnumOptions($fullType)) {
            $type = 'select';
            $options = collect($enumOptions)
                ->map(fn(string $option) => ['value' => $option, 'label' => Str::headline($option)])
                ->all();
        } elseif ($this->looksLikeFileField($name)) {
            $type = 'file';
        } elseif ($castType === 'boolean' || in_array($columnType, ['bool', 'boolean'], true)) {
            $type = 'boolean';
        } elseif (in_array($castType, ['date', 'immutable_date'], true) || $columnType === 'date') {
            $type = 'date';
        } elseif (
            in_array($castType, ['datetime', 'immutable_datetime'], true)
            || in_array($columnType, ['datetime', 'timestamp'], true)
        ) {
            $type = 'datetime';
        } elseif (
            str_contains((string) $castType, 'decimal')
            || in_array($columnType, ['decimal', 'double', 'float'], true)
        ) {
            $type = 'decimal';
        } elseif ($this->looksLikeTimeField($name)) {
            $type = 'time';
        } elseif ($this->looksLikeColorField($name)) {
            $type = 'color';
        } elseif (in_array($columnType, ['integer', 'bigint', 'mediumint', 'smallint', 'tinyint', 'year'], true)) {
            $type = 'number';
        } elseif ($this->looksLikeEmailField($name)) {
            $type = 'email';
        } elseif ($this->looksLikePhoneField($name)) {
            $type = 'tel';
        } elseif ($this->looksLikeUrlField($name)) {
            $type = 'url';
        } elseif (in_array($columnType, ['text', 'mediumtext', 'longtext', 'json'], true) || $this->looksLikeTextareaField($name)) {
            $type = 'textarea';
        }

        return [
            'name' => $name,
            'label' => Str::headline($name),
            'type' => $type,
            'rich_text' => $type === 'textarea' && $this->looksLikeRichTextField($name),
            'full_width' => $this->isFullWidthField($name, $type),
            'required' => ! ($column['nullable'] ?? true) && ($column['default'] ?? null) === null && ! ($column['auto_increment'] ?? false),
            'nullable' => $column['nullable'] ?? true,
            'default' => $column['default'] ?? null,
            'unique' => in_array($name, $uniqueColumns, true),
            'column_type' => $columnType,
            'cast_type' => $castType,
            'options' => $options,
            'foreign_table' => $foreignKey['foreign_table'] ?? null,
            'foreign_column' => $foreignKey['foreign_column'] ?? null,
            'is_image' => $isImage,
            'table' => $table,
            'accept' => $this->resolveAcceptAttribute($type, $isImage),
            'step' => $this->resolveInputStep($type),
            'placeholder' => $this->resolvePlaceholder($name, $type),
            'toggle_text' => $this->resolveToggleText($name),
            'min' => $this->resolveInputMin($columnType, $type),
            'max' => $this->resolveInputMax($columnType, $type),
        ];
    }

    protected function mapForeignKeys(string $table): array
    {
        $map = [];

        foreach (Schema::getForeignKeys($table) as $foreignKey) {
            if (count($foreignKey['columns']) !== 1 || count($foreignKey['foreign_columns']) !== 1) {
                continue;
            }

            $map[$foreignKey['columns'][0]] = [
                'foreign_table' => $foreignKey['foreign_table'],
                'foreign_column' => $foreignKey['foreign_columns'][0],
            ];
        }

        return $map;
    }

    protected function getUniqueColumns(string $table): array
    {
        return collect(Schema::getIndexes($table))
            ->filter(fn(array $index) => $index['unique'] && count($index['columns']) === 1)
            ->pluck('columns')
            ->flatten()
            ->values()
            ->all();
    }

    protected function detectTitleField(array $fields): ?string
    {
        $candidates = [
            'name',
            'nama_lengkap',
            'nama_sekolah',
            'nama_gelombang',
            'nama_album',
            'nama_jurusan',
            'nama_ekskul',
            'judul',
            'no_pendaftaran',
            'nip',
            'npsn',
        ];

        foreach ($candidates as $candidate) {
            $field = collect($fields)->firstWhere('name', $candidate);

            if ($field) {
                return $field['name'];
            }
        }

        return $fields[0]['name'] ?? null;
    }

    protected function parseEnumOptions(string $definition): array
    {
        if (! str_starts_with($definition, 'enum(')) {
            return [];
        }

        preg_match_all("/'([^']+)'/", $definition, $matches);

        return $matches[1] ?? [];
    }

    protected function getRelationOptions(array $foreignKey): array
    {
        $relatedModelClass = $this->resolveModelByTable($foreignKey['foreign_table']);

        if (! $relatedModelClass) {
            return [];
        }

        /** @var \Illuminate\Database\Eloquent\Model $relatedModel */
        $relatedModel = app($relatedModelClass);
        $labelField = $this->detectRelationLabelField($relatedModel);
        $valueField = $foreignKey['foreign_column'] ?? $relatedModel->getKeyName();

        return $relatedModelClass::query()
            ->orderBy($labelField)
            ->limit(300)
            ->get()
            ->map(fn(Model $item) => [
                'value' => (string) $item->{$valueField},
                'label' => (string) ($item->{$labelField} ?? '#' . $item->getKey()),
            ])
            ->all();
    }

    protected function resolveModelByTable(string $table): ?string
    {
        $map = collect(config('admin_resources', []))
            ->mapWithKeys(function (array $config) {
                /** @var \Illuminate\Database\Eloquent\Model $model */
                $model = app($config['model']);

                return [$model->getTable() => $config['model']];
            })
            ->all();

        $map[(new User())->getTable()] = User::class;

        return $map[$table] ?? null;
    }

    protected function detectRelationLabelField(Model $model): string
    {
        $fillable = $model->getFillable();
        $candidates = [
            'name',
            'nama_lengkap',
            'nama_sekolah',
            'nama_jurusan',
            'nama_album',
            'nama_gelombang',
            'judul',
            'no_pendaftaran',
            'nip',
            'email',
        ];

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fillable, true)) {
                return $candidate;
            }
        }

        return $fillable[0] ?? $model->getKeyName();
    }

    protected function looksLikeFileField(string $name): bool
    {
        if (preg_match('/^struktur_(organisasi|perpustakaan)$/i', $name) === 1) {
            return true;
        }

        return preg_match('/(^|_)(foto|gambar|cover|logo|favicon|thumbnail|file|dokumen|sertifikat|akte|ijazah|nilai)(_|$)/i', $name) === 1;
    }

    protected function looksLikeImageField(string $name): bool
    {
        if (preg_match('/^struktur_(organisasi|perpustakaan)$/i', $name) === 1) {
            return true;
        }

        return preg_match('/(^|_)(foto|gambar|cover|logo|favicon|thumbnail)(_|$)/i', $name) === 1;
    }

    protected function looksLikeTextareaField(string $name): bool
    {
        return preg_match('/(alamat|deskripsi|konten|sejarah|visi|misi|sambutan|keterangan|fasilitas|prospek_karir|prestasi|embed_code)$/i', $name) === 1;
    }

    protected function looksLikeRichTextField(string $name): bool
    {
        return preg_match('/((deskripsi|konten|sejarah|visi|misi|keterangan|prospek_karir|prestasi)$|(^|_)sambutan(_|$))/i', $name) === 1;
    }

    protected function looksLikePhoneField(string $name): bool
    {
        return preg_match('/(telepon|no_hp|nomor_wa|whatsapp|phone|hp)$/i', $name) === 1;
    }

    protected function looksLikeColorField(string $name): bool
    {
        return preg_match('/(warna|color)$/i', $name) === 1;
    }

    protected function looksLikeTimeField(string $name): bool
    {
        return preg_match('/(^|_)(waktu|jam)(_|\z)/i', $name) === 1;
    }

    protected function looksLikeEmailField(string $name): bool
    {
        return str_contains($name, 'email');
    }

    protected function looksLikeUrlField(string $name): bool
    {
        if (preg_match('/^nama_/i', $name) === 1) {
            return false;
        }

        return preg_match('/(^|_)(website|link|url|youtube)(_|\z)/i', $name) === 1;
    }

    protected function isFullWidthField(string $name, string $type): bool
    {
        if (in_array($type, ['textarea', 'file'], true)) {
            return true;
        }

        return preg_match('/(alamat|konten|deskripsi|keterangan|embed_code|pesan_otomatis)$/i', $name) === 1;
    }

    protected function resolveAcceptAttribute(string $type, bool $isImage): ?string
    {
        if ($type !== 'file') {
            return null;
        }

        if ($isImage) {
            return 'image/*';
        }

        return '.pdf,.doc,.docx,.jpg,.jpeg,.png';
    }

    protected function resolveInputStep(string $type): ?string
    {
        return match ($type) {
            'decimal' => '0.01',
            'number' => '1',
            default => null,
        };
    }

    protected function resolveInputMin(string $columnType, string $type): ?string
    {
        if ($columnType === 'year') {
            return '1901';
        }

        return match ($type) {
            'number' => '0',
            default => null,
        };
    }

    protected function resolveInputMax(string $columnType, string $type): ?string
    {
        if ($columnType === 'year') {
            return '2155';
        }

        return null;
    }

    protected function resolvePlaceholder(string $name, string $type): ?string
    {
        return match ($type) {
            'email' => 'contoh@email.com',
            'url' => 'https://example.com',
            'tel' => '08xxxxxxxxxx',
            'color' => '#4f46e5',
            'number', 'decimal' => 'Masukkan angka',
            default => match (true) {
                preg_match('/slug/i', $name) === 1 => 'contoh-slug-data',
                preg_match('/kode/i', $name) === 1 => 'Masukkan kode',
                default => null,
            },
        };
    }

    protected function resolveToggleText(string $name): string
    {
        return match (true) {
            preg_match('/published/i', $name) === 1 => 'Centang jika ingin ditampilkan ke publik',
            preg_match('/read/i', $name) === 1 => 'Tandai sebagai sudah dibaca',
            preg_match('/replied/i', $name) === 1 => 'Tandai sebagai sudah dibalas',
            default => 'Aktifkan status ini',
        };
    }

    protected function storeUploadedFile(UploadedFile $file, string $resourceKey): string
    {
        $directory = public_path('storage/' . $resourceKey);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
        $file->move($directory, $filename);

        return $resourceKey . '/' . $filename;
    }

    protected function deleteStoredFile(string $path): void
    {
        $fullPath = public_path('storage/' . $path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
