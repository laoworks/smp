<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftar extends Model
{
    protected $table = 'pendaftars';

    protected $fillable = [
        'no_pendaftaran',
        'gelombang_id',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'anak_ke',
        'jumlah_saudara',
        'email',
        'no_hp',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'asal_sekolah',
        'nisn',
        'tahun_lulus',
        'ijazah_number',
        'ayah_nama',
        'ayah_pekerjaan',
        'ayah_pendidikan',
        'ayah_no_hp',
        'ibu_nama',
        'ibu_pekerjaan',
        'ibu_pendidikan',
        'ibu_no_hp',
        'wali_nama',
        'wali_pekerjaan',
        'wali_hubungan',
        'jurusan_pilihan_1',
        'jurusan_pilihan_2',
        'foto_kk',
        'foto_akte',
        'foto_ijazah',
        'foto_nilai',
        'foto_sertifikat',
        'status_verifikasi',
        'keterangan',
        'tanggal_daftar',
        'tanggal_verifikasi',
        'verifikator_id'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
        'tanggal_daftar' => 'datetime',
        'tanggal_verifikasi' => 'datetime'
    ];

    // Relasi: Pendaftar milik satu gelombang
    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(GelombangPpdb::class, 'gelombang_id');
    }

    // Relasi: Pendaftar milik satu jurusan pilihan 1
    public function jurusanPilihan1(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_pilihan_1');
    }

    // Relasi: Pendaftar milik satu jurusan pilihan 2
    public function jurusanPilihan2(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_pilihan_2');
    }

    // Relasi: Pendaftar diverifikasi oleh user
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
