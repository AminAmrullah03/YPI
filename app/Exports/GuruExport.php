<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuruExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Guru::with('lembaga');

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
            'NIK',
            'NUPTK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Telepon',
            'Pendidikan Terakhir',
            'Jabatan',
            'Mata Pelajaran',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Status Kepegawaian',
            'Status',
        ];
    }

    public function map($guru): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $guru->lembaga?->nama ?? '—',
            $guru->nik ?? '—',
            $guru->nuptk ?? '—',
            $guru->nama,
            $guru->jenis_kelamin_label,
            $guru->tempat_lahir ?? '—',
            $guru->tanggal_lahir?->format('d/m/Y') ?? '—',
            $guru->alamat ?? '—',
            $guru->telepon ?? '—',
            $guru->pendidikan_terakhir ?? '—',
            $guru->jabatan ?? '—',
            $guru->mata_pelajaran ?? '—',
            $guru->tanggal_masuk?->format('d/m/Y') ?? '—',
            $guru->tanggal_keluar?->format('d/m/Y') ?? 'Masih Aktif',
            $guru->status_kepegawaian_label,
            $guru->status_label,
        ];
    }
}
