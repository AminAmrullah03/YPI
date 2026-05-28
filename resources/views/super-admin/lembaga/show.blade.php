@extends('layouts.app')

@section('title', 'Detail Lembaga — ' . $lembaga->nama)
@section('page-title', 'Manajemen Lembaga')
@section('page-subtitle', 'Detail Lembaga')

@section('content')

<div class="content-area">

    {{-- Back & Action Buttons --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <a href="{{ route('super-admin.lembaga.index') }}" class="btn btn-secondary btn-sm">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
        </a>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('super-admin.lembaga.edit', $lembaga->id) }}" class="btn btn-primary btn-sm" style="background:#f59e0b;">
                <i class="ph ph-pencil-simple"></i> Ubah Lembaga
            </a>
        </div>
    </div>

    {{-- Info Card & Stats --}}
    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:20px; margin-bottom:28px;">
        
        {{-- Profile Card --}}
        <div class="card" style="display:flex; flex-direction:column; align-items:center; text-align:center;">
            @if($lembaga->logo)
                <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo" style="width:100px; height:100px; border-radius:18px; object-fit:cover; border:2px solid #e2e8f0; margin-bottom:16px;">
            @else
                <div style="width:100px; height:100px; background:linear-gradient(135deg, #10b981, #059669); border-radius:18px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:32px; margin-bottom:16px; box-shadow:0 8px 24px rgba(16,185,129,0.2);">
                    {{ substr($lembaga->nama, 0, 2) }}
                </div>
            @endif

            <h2 style="margin:0 0 4px; font-size:18px; font-weight:800; color:#1e293b;">{{ $lembaga->nama }}</h2>
            <span style="font-size:12px; font-weight:600; color:#0891b2; background:rgba(8,145,178,0.1); padding:3px 12px; border-radius:12px; margin-bottom:16px;">
                {{ $lembaga->jenis_label }}
            </span>

            <div style="width:100%; border-top:1px solid #f1f5f9; padding-top:16px; margin-top:8px; text-align:left; display:flex; flex-direction:column; gap:12px;">
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Kepala Lembaga</div>
                    <div style="font-size:14px; color:#1e293b; font-weight:600;">{{ $lembaga->kepala }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Telepon / WA</div>
                    <div style="font-size:14px; color:#1e293b; font-weight:600;">{{ $lembaga->telepon ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Alamat</div>
                    <div style="font-size:13.5px; color:#475569; line-height:1.4;">{{ $lembaga->alamat ?? 'Belum diatur' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Status Lembaga</div>
                    <div style="margin-top:4px;">
                        @if($lembaga->is_active)
                            <span class="badge badge-aktif">Aktif</span>
                        @else
                            <span class="badge badge-tidak_aktif">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Grid --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; flex:1;">
                
                {{-- Siswa Card --}}
                <div class="card" style="display:flex; align-items:center; gap:20px;">
                    <div style="width:60px; height:60px; background:rgba(16,185,129,0.1); border-radius:14px; display:flex; align-items:center; justify-content:center;">
                        <i class="ph ph-student" style="color:#10b981; font-size:28px;"></i>
                    </div>
                    <div>
                        <div style="font-size:32px; font-weight:800; color:#1e293b; line-height:1;">{{ $stats['siswa_aktif'] }}</div>
                        <div style="font-size:13px; color:#64748b; font-weight:600; margin-top:4px;">Siswa Aktif</div>
                        <div style="font-size:11px; color:#94a3b8; margin-top:2px;">dari {{ $stats['total_siswa'] }} terdaftar</div>
                    </div>
                </div>

                {{-- Guru Card --}}
                <div class="card" style="display:flex; align-items:center; gap:20px;">
                    <div style="width:60px; height:60px; background:rgba(8,145,178,0.1); border-radius:14px; display:flex; align-items:center; justify-content:center;">
                        <i class="ph ph-chalkboard-teacher" style="color:#0891b2; font-size:28px;"></i>
                    </div>
                    <div>
                        <div style="font-size:32px; font-weight:800; color:#1e293b; line-height:1;">{{ $stats['guru_aktif'] }}</div>
                        <div style="font-size:13px; color:#64748b; font-weight:600; margin-top:4px;">Guru Aktif</div>
                        <div style="font-size:11px; color:#94a3b8; margin-top:2px;">dari {{ $stats['total_guru'] }} terdaftar</div>
                    </div>
                </div>

            </div>

            {{-- Deskripsi / Catatan Tambahan --}}
            <div class="card" style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                <h3 style="margin:0 0 8px; font-size:15px; font-weight:700; color:#1e293b;">Catatan Yayasan</h3>
                <p style="margin:0; font-size:13.5px; color:#64748b; line-height:1.6;">
                    Lembaga ini merupakan salah satu satuan pendidikan formal/non-formal di bawah naungan Yayasan Pendidikan Islam PP Darus Sholah. Pengelolaan data siswa dan guru dilakukan secara berkala dan terpusat melalui sistem pelaporan DIGIDAS.
                </p>
            </div>
        </div>

    </div>

    {{-- Siswa & Guru Lists (Tabs or Split) --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        
        {{-- Recent Students --}}
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-student" style="color:#10b981;"></i>
                    Siswa Terbaru
                </div>
                <a href="{{ route('super-admin.siswa.index', ['jenis' => $lembaga->jenis]) }}" style="font-size:12px; font-weight:600; color:#10b981; text-decoration:none;">Lihat Semua</a>
            </div>
            
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:10px 20px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Siswa</th>
                        <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Kelas</th>
                        <th style="padding:10px 20px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($siswaList->isEmpty())
                        <tr>
                            <td colspan="3" style="padding:32px; text-align:center; color:#94a3b8; font-size:13px;">
                                Belum ada data siswa
                            </td>
                        </tr>
                    @else
                        @foreach($siswaList as $sis)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:12px 20px;">
                                <div style="font-weight:600; font-size:13.5px; color:#1e293b;">{{ $sis->nama }}</div>
                                <div style="font-size:11px; color:#94a3b8;">NIS: {{ $sis->nis }}</div>
                            </td>
                            <td style="padding:12px 16px; font-size:13px; color:#475569;">
                                {{ $sis->kelas }}
                            </td>
                            <td style="padding:12px 20px; text-align:center;">
                                <span class="badge badge-{{ $sis->status }}">{{ $sis->status_label }}</span>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Recent Teachers --}}
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-chalkboard-teacher" style="color:#0891b2;"></i>
                    Guru Terbaru
                </div>
                <a href="{{ route('super-admin.guru.index', ['jenis' => $lembaga->jenis]) }}" style="font-size:12px; font-weight:600; color:#0891b2; text-decoration:none;">Lihat Semua</a>
            </div>
            
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:10px 20px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Nama Guru</th>
                        <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Jabatan</th>
                        <th style="padding:10px 20px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($guruList->isEmpty())
                        <tr>
                            <td colspan="3" style="padding:32px; text-align:center; color:#94a3b8; font-size:13px;">
                                Belum ada data guru
                            </td>
                        </tr>
                    @else
                        @foreach($guruList as $gur)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:12px 20px;">
                                <div style="font-weight:600; font-size:13.5px; color:#1e293b;">{{ $gur->nama }}</div>
                                <div style="font-size:11px; color:#94a3b8;">{{ $gur->status_kepegawaian_label }}</div>
                            </td>
                            <td style="padding:12px 16px; font-size:13px; color:#475569;">
                                {{ $gur->jabatan }}
                            </td>
                            <td style="padding:12px 20px; text-align:center;">
                                <span class="badge badge-{{ $gur->status }}">{{ $gur->status_label }}</span>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
