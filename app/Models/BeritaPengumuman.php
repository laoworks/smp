<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BeritaPengumuman extends Model
{
    protected $table = 'berita_pengumumans';

    protected $fillable = [
        'judul',
        'slug',
        'jenis',
        'is_urgent',
        'konten',
        'gambar_utama',
        'penulis',
        'views',
        'is_published',
        'published_at',
        'expired_at'
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    // Relasi: Berita memiliki banyak kategori (many-to-many)
    public function kategori(): BelongsToMany
    {
        return $this->belongsToMany(KategoriBerita::class, 'berita_kategori', 'berita_id', 'kategori_id');
    }

    // Accessor untuk formatted tanggal
    public function getTanggalPostingAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y') : '-';
    }
}
