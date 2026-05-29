<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    protected $table = 'kalender_akademiks';

    protected $fillable = [
        'judul_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu',
        'tempat',
        'jenis',
        'target_audience',
        'warna',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];
}
