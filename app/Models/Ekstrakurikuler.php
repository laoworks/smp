<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ekstrakurikuler extends Model
{
    protected $table = 'ekstrakurikulers';

    protected $fillable = [
        'nama_ekskul',
        'pembina_id',
        'deskripsi',
        'prestasi',
        'jadwal_latihan',
        'tempat_latihan',
        'gambar',
        'kuota',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi: Ekstrakurikuler milik satu pembina (guru)
    public function pembina(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'pembina_id');
    }
}
