<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiswaExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder
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
            'Program',
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
            $siswa->program_label,
            $siswa->status_label,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '@', // NIS
            'D' => '@', // NIK
            'K' => '@', // Telepon Wali
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // Hindari konversi ke number/notasi ilmiah untuk NIS, NIK, dan No Telepon
        if (is_numeric($value) && strlen($value) > 4) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
