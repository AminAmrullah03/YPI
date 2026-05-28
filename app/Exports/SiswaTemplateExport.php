<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'NIS',
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
                'aktif',
            ]
        ];
    }
}
