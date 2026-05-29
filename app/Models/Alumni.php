<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    protected $table = 'alumnis';

    protected $fillable = [
        'nama_lengkap',
        'tahun_lulus',
        'jurusan_id',
        'foto',
        'universitas',
        'jurusan_kuliah',
        'tahun_masuk_kuliah',
        'pekerjaan',
        'perusahaan',
        'posisi',
        'email',
        'no_hp',
        'linkedin',
        'kisah_sukses',
        'prestasi_alumni',
        'is_featured',
        'is_verified'
    ];

    protected $casts = [
        'tahun_lulus' => 'integer',
        'tahun_masuk_kuliah' => 'integer',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean'
    ];

    // Relasi: Alumni milik satu jurusan
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }
}
