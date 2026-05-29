<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KategoriBerita extends Model
{
    protected $table = 'kategori_beritas';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi'
    ];

    // Relasi: Kategori dimiliki oleh banyak berita (many-to-many)
    public function berita(): BelongsToMany
    {
        return $this->belongsToMany(BeritaPengumuman::class, 'berita_kategori', 'kategori_id', 'berita_id');
    }
}
