<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $lembagaId;
    protected $status;

    public function __construct($lembagaId = null, $status = null)
    {
        $this->lembagaId = $lembagaId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Siswa::with('lembaga');

        if ($this->lembagaId) {
            $query->where('lembaga_id', $this->lembagaId);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Lembaga',
            'NIS',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Nama Wali',
            'Telepon Wali',
            'Tanggal Masuk',
            'Kelas',
            'Status',
        ];
    }

    public function map($siswa): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $siswa->lembaga?->nama ?? '—',
            $siswa->nis ?? '—',
            $siswa->nik ?? '—',
            $siswa->nama,
            $siswa->jenis_kelamin_label,
            $siswa->tempat_lahir ?? '—',
            $siswa->tanggal_lahir?->format('d/m/Y') ?? '—',
            $siswa->alamat ?? '—',
            $siswa->nama_wali ?? '—',
            $siswa->telepon_wali ?? '—',
            $siswa->tanggal_masuk?->format('d/m/Y') ?? '—',
            $siswa->kelas ?? '—',
            $siswa->status_label,
        ];
    }
}
