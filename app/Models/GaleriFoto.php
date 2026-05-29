<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriFoto extends Model
{
    protected $table = 'galeri_fotos';

    protected $fillable = [
        'album_id',
        'judul',
        'deskripsi',
        'foto',
        'urutan'
    ];

    // Relasi: Foto milik satu album
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
