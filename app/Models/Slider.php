<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'link',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
