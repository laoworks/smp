<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasis';

    protected $fillable = [
        'judul',
        'jenis',
        'tingkat',
        'juara',
        'tahun',
        'peserta_nama',
        'peserta_kelas',
        'sertifikat',
        'foto',
        'deskripsi'
    ];
}
