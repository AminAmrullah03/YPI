<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiswaTemplateExport extends DefaultValueBinder implements FromArray, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function headings(): array
    {
        return [
            'NISN',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Alamat',
            'Nama Wali',
            'Telepon Wali',
            'Tanggal Masuk (YYYY-MM-DD)',
            'Kelas',
            'Program (fullday/fulltime)',
            'Status (aktif/tidak_aktif/lulus/pindah)',
        ];
    }

    public function array(): array
    {
        return [
            [
                '12345',
                '3501234567890001',
                'Ahmad Fauzi',
                'L',
                'Jember',
                '2015-05-12',
                'Jl. Raya No. 45, Jember',
                'Bambang',
                '08123456789',
                '2021-07-15',
                'Kelas 4',
                'fullday',
                'aktif',
            ],
            [
                '12346',
                '3501234567890002',
                'Siti Aisyah',
                'P',
                'Jember',
                '2016-08-20',
                'Jl. Kenanga No. 12, Jember',
                'Rahmat',
                '08234567890',
                '2022-07-15',
                'Kelas 3',
                'fulltime',
                'aktif',
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@', // NISN
            'B' => '@', // NIK
            'I' => '@', // Telepon Wali
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // Paksa type string jika bernilai angka (seperti NIS, NIK, Telepon) agar Excel tidak mengubahnya ke scientific/notasi ilmiah
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
