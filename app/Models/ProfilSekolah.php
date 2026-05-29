<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolahs';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'nss',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'tahun_berdiri',
        'pendiri',
        'sejarah',
        'sambutan_kepala_sekolah',
        'visi',
        'misi',
        'akreditasi',
        'tahun_akreditasi',
        'logo',
        'favicon',
        'gambar_ilustrasi',
        'gambar_misi_1',
        'gambar_misi_2',
        'gambar_misi_3',
        'gambar_kepala_sekolah',
        'nama_kepala_sekolah',
        'struktur_perpustakaan'
    ];
}
