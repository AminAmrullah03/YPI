<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Pendataan YPI Darus Sholah</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 14px;
            color: #10b981;
            font-weight: 500;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .stats-summary {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .stats-summary td {
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
            border-radius: 8px;
        }
        .stats-summary .value {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }
        .stats-summary .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }
        h3 {
            font-size: 14px;
            color: #0f172a;
            border-left: 3px solid #10b981;
            padding-left: 8px;
            margin-bottom: 12px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            border: 1px solid #0f172a;
        }
        table.data-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            font-size: 12.5px;
        }
        table.data-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>YAYASAN PENDIDIKAN ISLAM PP DARUS SHOLAH</h1>
        <h2>DIGIDAS — Sistem Pendataan Terpadu Siswa & Guru</h2>
        <p>Tanggal Laporan: {{ date('d F Y') }} | Digenerate oleh: Super Admin</p>
    </div>

    <h3>Rangkuman Umum</h3>
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

    <h3>Ringkasan per Lembaga</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Lembaga</th>
                <th style="width: 15%;">Jenis</th>
                <th style="width: 15%; text-align: center;">Siswa Aktif</th>
                <th style="width: 15%; text-align: center;">Guru Aktif</th>
                <th style="width: 15%;">Kepala Lembaga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lembagaList as $index => $lem)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $lem->nama }}</strong></td>
                <td>{{ $lem->jenis_label }}</td>
                <td class="text-center" style="color: #059669; font-weight: bold;">{{ $lem->siswa->where('status', 'aktif')->count() }}</td>
                <td class="text-center" style="color: #0891b2; font-weight: bold;">{{ $lem->guru->where('status', 'aktif')->count() }}</td>
                <td>{{ $lem->kepala }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td style="text-align: center; border: none;">
                    <p>Mengetahui,</p>
                    <p style="font-weight: bold; margin-top: 60px;">Ketua Yayasan YPI Darus Sholah</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        DIGIDAS &copy; {{ date('Y') }} — Laporan Resmi Yayasan Pendidikan Islam PP Darus Sholah
    </div>

</body>
</html>
