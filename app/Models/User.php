<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'foto',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    // ================= RELASI =================

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function verifikasiPendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'verifikator_id');
    }

    // ================= ROLE HELPERS (OPSIONAL) =================

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isVerifikator(): bool
    {
        return $this->hasRole('verifikator');
    }

    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }
}
