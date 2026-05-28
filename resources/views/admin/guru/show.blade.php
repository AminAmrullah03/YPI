@extends('layouts.app')

@section('title', 'Detail Guru — ' . $guru->nama)
@section('page-title', 'Data Guru')
@section('page-subtitle', 'Detail Guru')

@section('content')

<div class="content-area">

    {{-- Back & Action Buttons --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary btn-sm">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-primary btn-sm" style="background:#f59e0b;">
            <i class="ph ph-pencil-simple"></i> Ubah Data Guru
        </a>
    </div>

    {{-- Main Profile Card --}}
    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:20px;">
        
        {{-- Profile Sidebar --}}
        <div class="card" style="display:flex; flex-direction:column; align-items:center; text-align:center;">
            @if($guru->foto)
                <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" style="width:140px; height:140px; border-radius:24px; object-fit:cover; border:3px solid #e2e8f0; margin-bottom:16px;">
            @else
                <div style="width:140px; height:140px; background:#f1f5f9; border-radius:24px; display:flex; align-items:center; justify-content:center; border:2px dashed #cbd5e1; margin-bottom:16px;">
                    <i class="ph ph-user" style="color:#94a3b8; font-size:64px;"></i>
                </div>
            @endif

            <h2 style="margin:0 0 4px; font-size:18px; font-weight:800; color:#1e293b;">{{ $guru->nama }}</h2>
            <div style="font-size:13px; color:#64748b; font-family:monospace; font-weight:600; margin-bottom:12px;">NIK: {{ $guru->nik ?? '—' }}</div>
            
            <div style="margin-bottom:16px;">
                <span class="badge badge-{{ $guru->status }}">{{ $guru->status_label }}</span>
            </div>

            <div style="width:100%; border-top:1px solid #f1f5f9; padding-top:16px; margin-top:8px; text-align:left; display:flex; flex-direction:column; gap:10px;">
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Lembaga</div>
                    <div style="font-size:13.5px; color:#1e293b; font-weight:600;">{{ $guru->lembaga?->nama }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal Terdaftar</div>
                    <div style="font-size:13.5px; color:#475569;">{{ $guru->created_at?->format('d F Y H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Petugas Penginput</div>
                    <div style="font-size:13.5px; color:#475569;">{{ $guru->createdBy?->name ?? 'System' }}</div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            {{-- Personal details --}}
            <div class="card">
                <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-identification-card" style="color:#0891b2;"></i>
                    Identitas & Data Diri
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Nama Lengkap</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->nama }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">NUPTK</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->nuptk ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tempat, Tanggal Lahir</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->tempat_lahir ?? '—' }}, {{ $guru->tanggal_lahir?->translatedFormat('d F Y') ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Jenis Kelamin</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->jenis_kelamin_label }} ({{ $guru->jenis_kelamin }})</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Pendidikan Terakhir</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->pendidikan_terakhir ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">No. Telepon / HP</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">
                            @if($guru->telepon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->telepon) }}" target="_blank" style="color:#0891b2; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:700;">
                                    <i class="ph ph-whatsapp-logo" style="font-size:16px;"></i>
                                    {{ $guru->telepon }}
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div style="grid-column:span 2;">
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Alamat Lengkap</div>
                        <div style="font-size:13.5px; color:#475569; line-height:1.5; margin-top:4px;">{{ $guru->alamat ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Kepegawaian --}}
            <div class="card">
                <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i class="ph ph-briefcase" style="color:#0891b2;"></i>
                    Data Kepegawaian & Jabatan
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Jabatan</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->jabatan }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Mata Pelajaran</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->mata_pelajaran ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Status Kepegawaian</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->status_kepegawaian_label }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Masa Tugas</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->masa_aktif }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal Mulai Bertugas</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->tanggal_masuk?->translatedFormat('d F Y') ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal Selesai Bertugas</div>
                        <div style="font-size:14px; color:#1e293b; font-weight:600; margin-top:2px;">{{ $guru->tanggal_keluar?->translatedFormat('d F Y') ?? 'Masih Aktif' }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
