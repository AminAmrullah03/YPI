<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuruTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'NIK',
            'NUPTK',
            'Nama Lengkap',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Alamat',
            'Telepon',
            'Pendidikan Terakhir',
            'Jabatan',
            'Mata Pelajaran',
            'Tanggal Masuk (YYYY-MM-DD)',
            'Status Kepegawaian (tetap/honorer/magang)',
            'Status (aktif/tidak_aktif)',
        ];
    }

    public function array(): array
    {
        return [
            [
                '3501234567890003',
                '1234567890123456',
                'Budi Santoso, S.Pd.',
                'L',
                'Jember',
                '1985-04-10',
                'Jl. Mawar No. 10, Jember',
                '08122334455',
                'S1 Pendidikan Fisika',
                'Guru Kelas',
                'Fisika',
                '2010-07-01',
                'tetap',
                'aktif',
            ],
            [
                '3501234567890004',
                '',
                'Dewi Lestari, S.Pd.',
                'P',
                'Jember',
                '1990-12-05',
                'Jl. Dahlia No. 5, Jember',
                '08566778899',
                'S1 Pendidikan Bahasa Inggris',
                'Guru Mata Pelajaran',
                'Bahasa Inggris',
                '2015-07-01',
                'honorer',
                'aktif',
            ]
        ];
    }
}
