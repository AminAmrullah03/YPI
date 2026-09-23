<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    protected $table = 'lembaga';

    protected $fillable = [
        'nama',
        'jenis',
        'alamat',
        'kepala',
        'telepon',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function setJenisAttribute($value)
    {
        $this->attributes['jenis'] = strtoupper($value);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getJenisLabelAttribute(): string
    {
        return strtoupper($this->jenis);
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function guru()
    {
        return $this->hasMany(Guru::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function totalSiswaAktif(): int
    {
        return $this->siswa()->where('status', 'aktif')->count();
    }

    public function totalGuruAktif(): int
    {
        return $this->guru()->where('status', 'aktif')->count();
    }
}
