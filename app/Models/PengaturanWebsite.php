<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PengaturanWebsite extends Model
{
    protected $table = 'pengaturan_websites';

    protected $fillable = [
        'nama_website',
        'logo',
        'logo_small',
        'favicon',
        'primary_color'
    ];

    // Method untuk mendapatkan pengaturan (singleton)
    public static function get()
    {
        return self::first() ?? self::create([
            'nama_website' => 'Website Sekolah',
            'primary_color' => '#4361ee'
        ]);
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('website_settings');
        });

        static::deleted(function () {
            Cache::forget('website_settings');
        });
    }
}
