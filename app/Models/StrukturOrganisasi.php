<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasis';

    protected $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'tahun',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tahun' => 'integer'
    ];
}
