<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $table = 'gurus';

    protected $fillable = [
        'nip',
        'nuptk',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'jurusan',
        'universitas',
        'tahun_lulus',
        'mata_pelajaran',
        'jabatan',
        'status',
        'foto',
        'email',
        'telepon',
        'alamat',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_lahir' => 'date'
    ];

    // Relasi: Guru memiliki banyak ekstrakurikuler sebagai pembina
    public function ekstrakurikuler(): HasMany
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }
}
