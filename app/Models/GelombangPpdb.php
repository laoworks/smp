<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GelombangPpdb extends Model
{
    protected $table = 'gelombang_ppdbs';

    protected $fillable = [
        'nama_gelombang',
        'periode_mulai',
        'periode_selesai',
        'kuota',
        'biaya_pendaftaran',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'biaya_pendaftaran' => 'decimal:2'
    ];

    // Relasi: Gelombang memiliki banyak pendaftar
    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'gelombang_id');
    }
}
