<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel_terkait',
        'data_sebelumnya',
        'data_sesudah',
        'ip_address',
        'user_agent'
    ];

    // Relasi: Log milik satu user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
