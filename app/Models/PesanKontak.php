<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanKontak extends Model
{
    protected $table = 'pesan_kontaks';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'subjek',
        'pesan',
        'is_read',
        'is_replied',
        'replied_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'replied_at' => 'datetime'
    ];
}
