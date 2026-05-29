<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResourceManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __construct(protected AdminResourceManager $manager) {}

    public function index(Request $request, string $resource): View
    {
        $definition = $this->manager->get($resource);
        $records = $this->buildIndexQuery($request, $definition)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->view('admin.resources._index-content', [
                'manager' => $this->manager,
                'resource' => $definition,
                'records' => $records,
            ]);
        }

        return view('admin.resources.index', [
            'manager' => $this->manager,
            'resource' => $definition,
            'records' => $records,
        ]);
    }

    public function exportExcel(Request $request): Response
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);

        abort_unless($definition['exportable'] ?? false, 404);

        $fields = $this->getExportFields($definition);
        $query = $this->buildIndexQuery($request, $definition);

        if ($resource === 'pendaftar') {
            $query->with(['gelombang', 'verifikator']);
        }

        $records = $query->latest('id')->get();
        $filename = $this->buildExportFilename($definition, 'xls');
        $content = view('admin.resources.export-excel', [
            'resource' => $definition,
            'fields' => $fields,
            'rows' => $this->buildExportRows($records, $fields),
            'exportedAt' => now(),
        ])->render();

        return response("\xEF\xBB\xBF" . $content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);

        abort_unless($definition['exportable'] ?? false, 404);

        $fields = $this->getExportFields($definition);
        $records = $this->buildIndexQuery($request, $definition)
            ->latest('id')
            ->get();
        $pdfView = $resource === 'pendaftar'
            ? 'admin.resources.export-pdf-pendaftar'
            : 'admin.resources.export-pdf';
        $paper = $resource === 'pendaftar' ? 'a4' : 'a4';
        $orientation = $resource === 'pendaftar' ? 'portrait' : 'landscape';

        return Pdf::loadView($pdfView, [
            'resource' => $definition,
            'fields' => $fields,
            'records' => $records,
            'rows' => $this->buildExportRows($records, $fields),
            'exportedAt' => now(),
        ])->setPaper($paper, $orientation)
            ->download($this->buildExportFilename($definition, 'pdf'));
    }

    public function create(Request $request): View
    {
        $resource = (string) $request->route('resource');

        return view('admin.resources.create', [
            'manager' => $this->manager,
            'resource' => $this->manager->get($resource),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);
        $modelClass = $definition['model'];

        $modelClass::query()->create(
            $this->manager->extractPayload($request, $definition)
        );

        return redirect()
            ->route($definition['route_name'] . '.index')
            ->with('success', $definition['label'] . ' berhasil ditambahkan');
    }

    public function show(Request $request, string|int $record): View
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);

        return view('admin.resources.show', [
            'manager' => $this->manager,
            'resource' => $definition,
            'item' => $this->manager->findRecord($resource, $record),
        ]);
    }

    public function edit(Request $request, string|int $record): View
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);

        return view('admin.resources.edit', [
            'manager' => $this->manager,
            'resource' => $definition,
            'item' => $this->manager->findRecord($resource, $record),
        ]);
    }

    public function update(Request $request, string|int $record): RedirectResponse
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);
        $item = $this->manager->findRecord($resource, $record);

        $item->update(
            $this->manager->extractPayload($request, $definition, $item)
        );

        return redirect()
            ->to('/admin/' . $resource)
            ->with('success', $definition['label'] . ' berhasil diupdate');
    }

    public function destroy(Request $request, string|int $record): RedirectResponse
    {
        $resource = (string) $request->route('resource');
        $definition = $this->manager->get($resource);
        $item = $this->manager->findRecord($resource, $record);

        $this->manager->deleteFilesForRecord($definition, $item);
        $item->delete();

        return redirect()
            ->to('/admin/' . $resource)
            ->with('success', $definition['label'] . ' berhasil dihapus');
    }

    protected function buildIndexQuery(Request $request, array $definition): Builder
    {
        $modelClass = $definition['model'];
        $query = $modelClass::query();
        $search = trim((string) $request->input('search'));

        if ($search !== '') {
            $searchableFields = collect($definition['fields'])
                ->reject(fn(array $field) => in_array($field['type'], ['file', 'textarea', 'boolean', 'date', 'datetime'], true))
                ->pluck('name')
                ->take(5)
                ->all();

            $query->where(function ($builder) use ($searchableFields, $search) {
                foreach ($searchableFields as $field) {
                    $builder->orWhere($field, 'like', '%' . $search . '%');
                }
            });
        }

        return $query;
    }

    protected function getExportFields(array $definition): array
    {
        return collect($definition['fields'])
            ->reject(fn(array $field) => $field['type'] === 'file')
            ->values()
            ->all();
    }

    protected function buildExportRows($records, array $fields): array
    {
        return $records->map(function ($record, $index) use ($fields) {
            $values = collect($fields)
                ->mapWithKeys(fn(array $field) => [$field['name'] => $this->formatExportValue($record, $field)])
                ->all();

            return [
                'no' => $index + 1,
                'title' => $this->manager->getTitle($record, [
                    'title_field' => null,
                ]),
                'values' => array_values($values),
                'mapped_values' => $values,
            ];
        })->all();
    }

    protected function formatExportValue($record, array $field): string
    {
        $value = $record->{$field['name']};

        if ($value === null || $value === '') {
            return '-';
        }

        return match ($field['type']) {
            'boolean' => $value ? 'Ya' : 'Tidak',
            'relation' => (string) ($this->manager->findOptionLabel($field, $value) ?? '#' . $value),
            'date' => optional($record->{$field['name']})->format('d-m-Y') ?? (string) $value,
            'datetime' => optional($record->{$field['name']})->format('d-m-Y H:i') ?? (string) $value,
            default => (string) ($field['rich_text']
                ? trim(strip_tags((string) $value))
                : Str::of((string) $value)->replace(["\r\n", "\r"], "\n")->toString()),
        };
    }

    protected function buildExportFilename(array $definition, string $extension): string
    {
        return Str::slug($definition['label']) . '-' . now()->format('Ymd-His') . '.' . $extension;
    }
}
