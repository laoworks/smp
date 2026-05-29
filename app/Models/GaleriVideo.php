<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriVideo extends Model
{
    protected $table = 'galeri_videos';

    protected $fillable = [
        'judul',
        'deskripsi',
        'url_youtube',
        'embed_code',
        'thumbnail',
        'tanggal',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal' => 'date'
    ];

    // Accessor untuk embed URL YouTube
    public function getEmbedUrlAttribute()
    {
        if ($this->url_youtube) {
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $this->url_youtube, $matches);
            return $matches[1] ?? null;
        }
        return null;
    }
}
