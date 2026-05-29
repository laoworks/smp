<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanWa extends Model
{
    protected $table = 'pengaturan_was';

    protected $fillable = [
        'nomor_wa',
        'pesan_otomatis',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
