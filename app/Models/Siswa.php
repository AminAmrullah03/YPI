<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    const STATUS_AKTIF       = 'aktif';
    const STATUS_TIDAK_AKTIF = 'tidak_aktif';
    const STATUS_LULUS       = 'lulus';
    const STATUS_PINDAH      = 'pindah';

    const STATUS_LIST = [
        'aktif'       => 'Aktif',
        'tidak_aktif' => 'Tidak Aktif',
        'lulus'       => 'Lulus',
        'pindah'      => 'Pindah',
    ];

    const PROGRAM_LIST = [
        'fullday'  => 'Fullday',
        'fulltime' => 'Fulltime',
    ];

    const JENIS_KELAMIN = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
    ];

    protected $table = 'siswa';

    protected $fillable = [
        'lembaga_id',
        'nis',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'nama_wali',
        'telepon_wali',
        'tanggal_masuk',
        'kelas',
        'program',
        'status',
        'foto',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir'  => 'date',
            'tanggal_masuk'  => 'date',
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

    public function getJenisKelaminLabelAttribute(): string
    {
        return self::JENIS_KELAMIN[$this->jenis_kelamin] ?? $this->jenis_kelamin;
    }

    public function getProgramLabelAttribute(): string
    {
        return self::PROGRAM_LIST[$this->program] ?? $this->program;
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-student.png');
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
