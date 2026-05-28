<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data — {{ $lembaga->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 4px 0 0;
            font-size: 13px;
            color: #10b981;
            font-weight: 500;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #64748b;
        }
        .stats-summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .stats-summary td {
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .stats-summary .value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .stats-summary .label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 4px;
        }
        h3 {
            font-size: 12px;
            color: #0f172a;
            border-left: 3px solid #10b981;
            padding-left: 6px;
            margin-bottom: 8px;
            margin-top: 15px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 6px 8px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
            border: 1px solid #0f172a;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>YAYASAN PENDIDIKAN ISLAM PP DARUS SHOLAH</h1>
        <h2>{{ $lembaga->nama }} ({{ $lembaga->jenis_label }})</h2>
        <p>Laporan Rekapitulasi Data Siswa & Guru | Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <h3>Rangkuman Lembaga</h3>
    <table class="stats-summary">
        <tr>
            <td style="width: 25%;">
                <div class="value">{{ number_format($siswaAktif) }}</div>
                <div class="label">Siswa Aktif</div>
            </td>
            <td style="width: 25%;">
                <div class="value">{{ number_format($totalSiswa) }}</div>
                <div class="label">Siswa Terdaftar</div>
            </td>
            <td style="width: 25%;">
                <div class="value">{{ number_format($guruAktif) }}</div>
                <div class="label">Guru Aktif</div>
            </td>
            <td style="width: 25%;">
                <div class="value">{{ number_format($totalGuru) }}</div>
                <div class="label">Guru Terdaftar</div>
            </td>
        </tr>
    </table>

    <h3>Daftar Siswa</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 35%;">Nama Lengkap</th>
                <th style="width: 10%; text-align: center;">JK</th>
                <th style="width: 20%;">Kelas</th>
                <th style="width: 15%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @if($siswaList->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Belum ada data siswa.</td>
                </tr>
            @else
                @foreach($siswaList as $index => $sis)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sis->nis ?? '—' }}</td>
                    <td><strong>{{ $sis->nama }}</strong></td>
                    <td class="text-center">{{ $sis->jenis_kelamin }}</td>
                    <td>{{ $sis->kelas ?? '—' }}</td>
                    <td class="text-center">{{ $sis->status_label }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="header">
        <h1>YAYASAN PENDIDIKAN ISLAM PP DARUS SHOLAH</h1>
        <h2>{{ $lembaga->nama }} ({{ $lembaga->jenis_label }})</h2>
        <p>Laporan Rekapitulasi Data Siswa & Guru | Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <h3>Daftar Guru / Tenaga Pendidik</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 20%;">NIK</th>
                <th style="width: 35%;">Nama Lengkap</th>
                <th style="width: 15%;">Jabatan</th>
                <th style="width: 15%;">Kepegawaian</th>
                <th style="width: 10%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @if($guruList->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Belum ada data guru.</td>
                </tr>
            @else
                @foreach($guruList as $index => $gur)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $gur->nik }}</td>
                    <td><strong>{{ $gur->nama }}</strong></td>
                    <td>{{ $gur->jabatan }}</td>
                    <td>{{ $gur->status_kepegawaian_label }}</td>
                    <td class="text-center">{{ $gur->status_label }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div style="margin-top: 40px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%; border: none;"></td>
                <td style="text-align: center; border: none;">
                    <p>Mengetahui,</p>
                    <p style="font-weight: bold; margin-top: 50px;">{{ $lembaga->kepala }}</p>
                    <p style="font-size: 10px; color: #64748b; margin: 0;">Kepala Lembaga {{ $lembaga->jenis_label }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        DIGIDAS &copy; {{ date('Y') }} — Laporan Resmi {{ $lembaga->nama }}
    </div>

</body>
</html>
