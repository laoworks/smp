<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiPpdb extends Model
{
    protected $table = 'notifikasi_ppdbs';

    protected $fillable = [
        'pendaftar_id',
        'jenis_notifikasi',
        'pesan',
        'dikirim_via',
        'status',
        'dikirim_pada'
    ];

    protected $casts = [
        'dikirim_pada' => 'datetime'
    ];

    // Relasi: Notifikasi milik satu pendaftar
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
