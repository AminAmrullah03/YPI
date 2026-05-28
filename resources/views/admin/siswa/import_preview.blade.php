@extends('layouts.app')

@section('title', 'Preview Import Siswa')
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Preview Import')

@section('content')

<div class="content-area">

    {{-- Alert Summary --}}
    <div style="margin-bottom:24px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div style="background:#d1fae5; border-left:4px solid #10b981; padding:16px; border-radius:8px; display:flex; align-items:center; gap:12px;">
                <i class="ph ph-check-circle" style="color:#065f46; font-size:28px;"></i>
                <div>
                    <div style="font-weight:700; color:#065f46; font-size:14px;">{{ count($validRows) }} Data Valid</div>
                    <div style="font-size:12px; color:#065f46; margin-top:2px;">Siap diimport ke database.</div>
                </div>
            </div>
            <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:16px; border-radius:8px; display:flex; align-items:center; gap:12px;">
                <i class="ph ph-warning-circle" style="color:#991b1b; font-size:28px;"></i>
                <div>
                    <div style="font-weight:700; color:#991b1b; font-size:14px;">{{ count($invalidRows) }} Data Error</div>
                    <div style="font-size:12px; color:#991b1b; margin-top:2px;">Data bermasalah dan akan dilewati.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Forms --}}
    <div style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <a href="{{ route('admin.siswa.import-form') }}" class="btn btn-secondary">
            <i class="ph ph-arrow-left"></i> Unggah Ulang File
        </a>

        @if(count($validRows) > 0)
            <form method="POST" action="{{ route('admin.siswa.import') }}">
                @csrf
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-primary" style="padding:12px 24px; border-radius:10px; font-size:14.5px;">
                    <i class="ph ph-check-square"></i> Konfirmasi & Simpan {{ count($validRows) }} Data
                </button>
            </form>
        @endif
    </div>

    {{-- Error List Card --}}
    @if(count($invalidRows) > 0)
        <div class="card" style="margin-bottom:28px; border-color:#fca5a5; background:#fffbfb;">
            <div style="font-size:15px; font-weight:700; color:#991b1b; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-warning-circle" style="color:#ef4444;"></i>
                Rincian Data Error (Akan Dilewati)
            </div>
            
            <div class="table-wrapper" style="box-shadow:none; border:1px solid #fee2e2;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#fee2e2;">
                            <th style="padding:10px 16px; color:#991b1b; width:80px; text-align:center;">Baris</th>
                            <th style="padding:10px 16px; color:#991b1b; width:220px;">Nama di File</th>
                            <th style="padding:10px 16px; color:#991b1b;">Kesalahan / Masalah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invalidRows as $err)
                        <tr>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#ef4444;">{{ $err['row'] }}</td>
                            <td style="padding:10px 16px; font-weight:600; color:#334155;">{{ $err['nama'] }}</td>
                            <td style="padding:10px 16px;">
                                <ul style="margin:0; padding-left:16px; color:#b91c1c; font-size:12.5px; display:flex; flex-direction:column; gap:2px;">
                                    @foreach($err['errors'] as $msg)
                                        <li>{{ $msg }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Valid List Card --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
            <i class="ph ph-check-circle" style="color:#10b981; font-size:20px;"></i>
            Data Valid (Akan Disimpan)
        </div>
        
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:10px 20px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Nama Siswa</th>
                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">NIS / NIK</th>
                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Kelas</th>
                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:60px;">JK</th>
                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Wali / Telepon</th>
                    <th style="padding:10px 20px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:100px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($validRows))
                    <tr>
                        <td colspan="6" style="padding:32px; text-align:center; color:#94a3b8; font-size:13.5px;">
                            Tidak ada data valid yang siap disimpan.
                        </td>
                    </tr>
                @else
                    @foreach($validRows as $row)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px 20px;">
                            <div style="font-weight:600; font-size:13.5px; color:#1e293b;">{{ $row['nama'] }}</div>
                            <div style="font-size:11px; color:#94a3b8;">Lahir: {{ $row['tempat_lahir'] ?? '—' }}, {{ $row['tanggal_lahir'] ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:12.5px; color:#475569;">
                            <div>NIS: {{ $row['nis'] ?? '—' }}</div>
                            <div>NIK: {{ $row['nik'] ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569; font-weight:500;">
                            {{ $row['kelas'] ?? '—' }}
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">
                            {{ $row['jenis_kelamin'] }}
                        </td>
                        <td style="padding:12px 16px; font-size:12.5px; color:#475569;">
                            <div>{{ $row['nama_wali'] ?? '—' }}</div>
                            <div>{{ $row['telepon_wali'] ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <span class="badge badge-{{ $row['status'] }}">{{ \App\Models\Siswa::STATUS_LIST[$row['status']] ?? $row['status'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

</div>

@endsection
