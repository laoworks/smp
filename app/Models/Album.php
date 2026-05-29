<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $table = 'albums';

    protected $fillable = [
        'nama_album',
        'deskripsi',
        'tanggal',
        'cover',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal' => 'date'
    ];

    // Relasi: Album memiliki banyak foto
    public function foto(): HasMany
    {
        return $this->hasMany(GaleriFoto::class);
    }
}
