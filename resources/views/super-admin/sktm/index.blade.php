@extends('layouts.app')

@section('title', 'Verifikasi SKTM Siswa')
@section('page-title', 'Verifikasi SKTM')

@section('content')
<style>
    .verify-root { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Welcome Banner */
    .verify-banner {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px; padding: 28px 32px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.05);
    }
    .verify-banner::before {
        content: ''; position: absolute; top: -80px; right: -60px;
        width: 260px; height: 260px; border-radius: 50%;
        background: rgba(16,185,129,0.06); pointer-events: none;
    }
    .verify-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0 0 4px; }
    .verify-sub   { color: #94a3b8; font-size: 0.875rem; margin: 0; font-weight:500; }

    /* Action Modal Styling (Pure HTML/CSS Modal using :target or Alpine.js) */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 100;
        animation: fadeIn 0.2s ease-out;
    }
    .modal-content {
        background: #fff; border-radius: 20px;
        width: 100%; max-width: 460px; padding: 28px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        animation: scaleIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="verify-root" x-data="{ showRejectModal: false, rejectSiswaId: null, rejectSiswaNama: '' }">

    {{-- Welcome Banner --}}
    <div class="verify-banner">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; position:relative; z-index:1;">
            <div>
                <h1 class="verify-title">Verifikasi Dokumen SKTM 📁</h1>
                <p class="verify-sub">Proses berkas permohonan keringanan biaya / SKTM siswa dari satuan lembaga pendidikan YPI.</p>
            </div>
            <div style="background: rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); padding:10px 20px; border-radius:14px; color:#10b981; font-weight:700; text-align:center;">
                <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.04em;">Antrean Saat Ini</div>
                <div style="font-size:24px; font-variant-numeric:tabular-nums; margin-top:2px;">{{ $siswaPending->total() }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
            <i class="ph ph-clock" style="color:#f59e0b; font-size:20px;"></i>
            Menunggu Persetujuan Yayasan
        </div>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:220px;">Siswa</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:180px;">Lembaga</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:120px;">Kelas / Program</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:160px; text-align:center;">Dokumen Pendukung</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:140px; text-align:center;">Tanggal Ajukan</th>
                    <th style="padding:12px 20px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9; width:200px;">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @if($siswaPending->isEmpty())
                    <tr>
                        <td colspan="6" style="padding:48px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-folder-open" style="font-size:44px; display:block; margin-bottom:8px;"></i>
                            Antrean bersih! Belum ada berkas SKTM yang dikirimkan oleh Admin Lembaga.
                        </td>
                    </tr>
                @else
                    @foreach($siswaPending as $sis)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 20px;">
                            <div style="font-weight:700; font-size:14px; color:#1e293b;">{{ $sis->nama }}</div>
                            <div style="font-size:11.5px; color:#64748b; font-family:monospace; margin-top:2px;">NISN: {{ $sis->nis ?? '—' }} &middot; NIK: {{ $sis->nik ?? '—' }}</div>
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:10px; font-weight:700; color:#0891b2; background:rgba(8,145,178,0.1); padding:2px 6px; border-radius:8px; text-transform:uppercase;">
                                {{ $sis->lembaga?->jenis_label }}
                            </span>
                            <div style="font-size:12.5px; color:#334155; margin-top:4px; font-weight:500;">{{ $sis->lembaga?->nama }}</div>
                        </td>
                        <td style="padding:14px 16px; font-size:13px; color:#334155;">
                            <div style="font-weight:600;">{{ $sis->kelas ?? '—' }}</div>
                            <div style="font-size:11px; color:#10b981; font-weight:700; margin-top:2px;">{{ $sis->program_label }}</div>
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            @if($sis->dokumen_sktm)
                                <a href="{{ route('super-admin.sktm.berkas', $sis->id) }}" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:700; color:#0891b2; border-color:#0891b2; background:rgba(8,145,178,0.04);">
                                    <i class="ph ph-file-pdf" style="font-size:16px;"></i> Buka Berkas
                                </a>
                            @else
                                <span style="font-style:italic; color:#94a3b8; font-size:12px;">Tanpa file</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center; font-size:12.5px; color:#64748b;">
                            {{ $sis->updated_at?->format('d/m/Y') ?? '—' }}
                            <div style="font-size:10.5px; color:#94a3b8; margin-top:2px;">{{ $sis->updated_at?->diffForHumans() }}</div>
                        </td>
                        <td style="padding:14px 20px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:8px;">
                                {{-- ACC / Approve Button --}}
                                <form method="POST" action="{{ route('super-admin.sktm.approve', $sis->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan SKTM siswa ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" style="background:#10b981; border:none; display:inline-flex; align-items:center; gap:4px; font-weight:700;">
                                        <i class="ph ph-check-bold"></i> Setujui
                                    </button>
                                </form>

                                {{-- Reject Trigger Button --}}
                                <button type="button" @click="showRejectModal = true; rejectSiswaId = {{ $sis->id }}; rejectSiswaNama = '{{ $sis->nama }}'" class="btn btn-secondary btn-sm" style="color:#ef4444; border-color:#fca5a5; background:#fff5f5; display:inline-flex; align-items:center; gap:4px; font-weight:700;">
                                    <i class="ph ph-x-bold"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($siswaPending->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $siswaPending->links() }}
        </div>
    @endif

    {{-- Rejection Modal --}}
    <template x-if="showRejectModal">
        <div class="modal-backdrop" @click.self="showRejectModal = false">
            <div class="modal-content">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">
                    <h3 style="margin:0; font-size:16px; font-weight:800; color:#1e293b;">
                        Tolak Pengajuan SKTM
                    </h3>
                    <button type="button" @click="showRejectModal = false" style="background:none; border:none; cursor:pointer; color:#94a3b8; font-size:20px;">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                
                <p style="font-size:13.5px; color:#475569; margin:0 0 16px; line-height:1.4;">
                    Anda akan menolak berkas pengajuan SKTM milik siswa <strong x-text="rejectSiswaNama" style="color:#0f172a;"></strong>. Harap masukkan alasan penolakan secara jelas:
                </p>

                <form :action="'{{ url('super-admin/sktm') }}/' + rejectSiswaId + '/reject'" method="POST">
                    @csrf
                    <div style="margin-bottom:20px;">
                        <label for="keterangan_sktm" class="form-label" style="font-weight:700;">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
                        <textarea 
                            name="keterangan_sktm" 
                            id="keterangan_sktm" 
                            class="form-textarea" 
                            rows="4" 
                            placeholder="Contoh: Lampiran file dokumen SKTM buram atau masa berlakunya sudah habis..." 
                            required
                        ></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:12px; border-top:1px solid #f1f5f9; padding-top:16px;">
                        <button type="button" @click="showRejectModal = false" class="btn btn-secondary btn-sm" style="font-weight:600;">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="background:#ef4444; border:none; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                            <i class="ph ph-x-circle"></i> Simpan Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
