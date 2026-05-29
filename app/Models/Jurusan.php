<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $table = 'jurusans';

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'singkatan',
        'deskripsi',
        'kurikulum',
        'prospek_karir',
        'fasilitas',
        'gambar',
        'thumbnail',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi: Jurusan memiliki banyak alumni
    public function alumni(): HasMany
    {
        return $this->hasMany(Alumni::class);
    }

    // Relasi: Jurusan memiliki banyak pendaftar (pilihan 1)
    public function pendaftarPilihan1(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'jurusan_pilihan_1');
    }

    // Relasi: Jurusan memiliki banyak pendaftar (pilihan 2)
    public function pendaftarPilihan2(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'jurusan_pilihan_2');
    }
}
