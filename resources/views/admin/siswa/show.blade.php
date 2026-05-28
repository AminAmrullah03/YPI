@extends('layouts.app')

@section('title', 'Detail Siswa — ' . $siswa->nama)
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Detail Siswa')

@section('content')

<div class="content-area">

    {{-- Back & Action Buttons --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary btn-sm">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-primary btn-sm" style="background:#f59e0b;">
            <i class="ph ph-pencil-simple"></i> Ubah Data Siswa
        </a>
    </div>

    {{-- Main Profile Card --}}
    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:20px;">
        
        {{-- Profile Sidebar --}}
        <div class="card" style="display:flex; flex-direction:column; align-items:center; text-align:center;">
            @if($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" style="width:140px; height:140px; border-radius:24px; object-fit:cover; border:3px solid #e2e8f0; margin-bottom:16px;">
            @else
                <div style="width:140px; height:140px; background:#f1f5f9; border-radius:24px; display:flex; align-items:center; justify-content:center; border:2px dashed #cbd5e1; margin-bottom:16px;">
                    <i class="ph ph-user" style="color:#94a3b8; font-size:64px;"></i>
                </div>
            @endif

            <h2 style="margin:0 0 4px; font-size:18px; font-weight:800; color:#1e293b;">{{ $siswa->nama }}</h2>
            <div style="font-size:13px; color:#64748b; font-family:monospace; font-weight:600; margin-bottom:12px;">NIS: {{ $siswa->nis ?? '—' }}</div>
            
            <div style="margin-bottom:16px;">
                <span class="badge badge-{{ $siswa->status }}">{{ $siswa->status_label }}</span>
            </div>

            <div style="width:100%; border-top:1px solid #f1f5f9; padding-top:16px; margin-top:8px; text-align:left; display:flex; flex-direction:column; gap:10px;">
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Lembaga</div>
                    <div style="font-size:13.5px; color:#1e293b; font-weight:600;">{{ $siswa->lembaga?->nama }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal Terdaftar</div>
                    <div style="font-size:13.5px; color:#475569;">{{ $siswa->created_at?->format('d F Y H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Petugas Penginput</div>
                    <div style="font-size:13.5px; color:#475569;">{{ $siswa->createdBy?->name ?? 'System' }}</div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            {{-- Personal details --}}
            <div class="card">
                <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-identification-card" style="color:#10b981;"></i>
                    Identitas & Data Diri
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Nama Lengkap</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->nama }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Nomor Induk Kependudukan (NIK)</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->nik ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tempat, Tanggal Lahir</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->tempat_lahir ?? '—' }}, {{ $siswa->tanggal_lahir?->translatedFormat('d F Y') ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Jenis Kelamin</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->jenis_kelamin_label }} ({{ $siswa->jenis_kelamin }})</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Kelas / Tingkat</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->kelas ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Program Layanan</div>
                        <div style="font-size:14px; color:#10b981; font-weight:700; margin-top:2px;">{{ $siswa->program_label }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal Mulai Aktif</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->tanggal_masuk?->translatedFormat('d F Y') ?? '—' }}</div>
                    </div>
                    <div style="grid-column:span 2;">
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Alamat Lengkap</div>
                        <div style="font-size:13.5px; color:#475569; line-height:1.5; margin-top:4px;">{{ $siswa->alamat ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Wali details --}}
            <div class="card">
                <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-users-three" style="color:#10b981;"></i>
                    Data Orang Tua / Wali
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Nama Orang Tua / Wali</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $siswa->nama_wali }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Telepon Wali</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">
                            @if($siswa->telepon_wali)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->telepon_wali) }}" target="_blank" style="color:#10b981; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:700;">
                                    <i class="ph ph-whatsapp-logo" style="font-size:16px;"></i>
                                    {{ $siswa->telepon_wali }}
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
