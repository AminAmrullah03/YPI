<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Role constants
    const ROLE_SUPER_ADMIN   = 'super_admin';
    const ROLE_ADMIN_LEMBAGA = 'admin_lembaga';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'lembaga_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdminLembaga(): bool
    {
        return $this->role === self::ROLE_ADMIN_LEMBAGA;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
