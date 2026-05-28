<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    const STATUS_AKTIF       = 'aktif';
    const STATUS_TIDAK_AKTIF = 'tidak_aktif';
    const STATUS_KELUAR      = 'keluar';
    const STATUS_PENSIUN     = 'pensiun';

    const STATUS_LIST = [
        'aktif'       => 'Aktif',
        'tidak_aktif' => 'Tidak Aktif',
        'keluar'      => 'Keluar',
        'pensiun'     => 'Pensiun',
    ];

    const STATUS_KEPEGAWAIAN_LIST = [
        'tetap'   => 'Guru Tetap',
        'honorer' => 'Honorer',
        'magang'  => 'Magang',
    ];

    const JENIS_KELAMIN = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
    ];

    protected $table = 'guru';

    protected $fillable = [
        'lembaga_id',
        'nik',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'pendidikan_terakhir',
        'jabatan',
        'mata_pelajaran',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_kepegawaian',
        'status',
        'foto',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir'  => 'date',
            'tanggal_masuk'  => 'date',
            'tanggal_keluar' => 'date',
        ];
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function scopeByLembaga($query, int $lembagaId)
    {
        return $query->where('lembaga_id', $lembagaId);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LIST[$this->status] ?? $this->status;
    }

    public function getStatusKepegawaianLabelAttribute(): string
    {
        return self::STATUS_KEPEGAWAIAN_LIST[$this->status_kepegawaian] ?? $this->status_kepegawaian;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return self::JENIS_KELAMIN[$this->jenis_kelamin] ?? $this->jenis_kelamin;
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-teacher.png');
    }

    public function getMasaAktifAttribute(): string
    {
        $masuk = $this->tanggal_masuk?->format('M Y') ?? '-';
        $keluar = $this->tanggal_keluar?->format('M Y') ?? 'Sekarang';
        return "{$masuk} – {$keluar}";
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
