<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    // Jenis lembaga yang tersedia
    const JENIS = ['TPQ', 'KB_TK', 'SD', 'SMP', 'SMA', 'MA'];

    const JENIS_LABEL = [
        'TPQ'   => 'TPQ',
        'KB_TK' => 'KB/TK',
        'SD'    => 'SD',
        'SMP'   => 'SMP',
        'SMA'   => 'SMA',
        'MA'    => 'MA',
    ];

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

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS_LABEL[$this->jenis] ?? $this->jenis;
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
