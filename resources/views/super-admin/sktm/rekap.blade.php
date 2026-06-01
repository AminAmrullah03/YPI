@extends('layouts.app')

@section('title', 'Rekap Siswa Kurang Mampu (SKTM)')
@section('page-title', 'Rekap SKTM Siswa')

@section('content')
<style>
    .sktm-root { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Banner */
    .sktm-banner {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, #4c0519 0%, #881337 40%, #be123c 100%);
        border-radius: 20px; padding: 28px 32px; margin-bottom: 28px;
        box-shadow: 0 8px 32px rgba(190,18,60,0.25), 0 0 0 1px rgba(244,63,94,0.15);
    }
    .sktm-banner::before {
        content: ''; position: absolute; top: -80px; right: -60px;
        width: 260px; height: 260px; border-radius: 50%;
        background: rgba(244,63,94,0.12); pointer-events: none;
    }
    .sktm-banner::after {
        content: ''; position: absolute; bottom: -50px; left: 35%;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(76,5,25,0.3); pointer-events: none;
    }

    /* SKTM Stat Cards */
    .sktm-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .sktm-stat {
        background: #fff; border-radius: 16px; padding: 20px;
        border: 1.5px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03);
        display: flex; align-items: center; gap: 16px;
        transition: all 0.2s;
    }
    .sktm-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .sktm-stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .sktm-stat-value { font-size: 1.875rem; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
    .sktm-stat-label { font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

    /* Per-lembaga summary */
    .lembaga-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }
    .lembaga-card {
        background: #fff; border: 1.5px solid #f1f5f9; border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        transition: all 0.2s;
    }
    .lembaga-card:hover { border-color: rgba(244,63,94,0.3); box-shadow: 0 4px 16px rgba(244,63,94,0.08); }
</style>

<div class="sktm-root">

    {{-- Banner --}}
    <div class="sktm-banner">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; position:relative; z-index:1;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 4px;">
                    🛡️ Rekap Siswa Kurang Mampu (SKTM)
                </h1>
                <p style="color:rgba(255,255,255,0.7); font-size:0.875rem; margin:0;">
                    Data rekap dan rekapitulasi siswa penerima SKTM seluruh lembaga YPI
                </p>
            </div>
            <div style="background:rgba(0,0,0,0.2); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:10px 18px; color:#fff; text-align:center; flex-shrink:0;">
                <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.05em; color:rgba(255,255,255,0.6);">Total Terverifikasi</div>
                <div style="font-size:1.75rem; font-weight:800; font-variant-numeric:tabular-nums; margin-top:2px;">{{ number_format($stats['total_sktm']) }}</div>
            </div>
        </div>
    </div>

    {{-- Stat Cards Ringkasan --}}
    <div class="sktm-stats">
        <div class="sktm-stat">
            <div class="sktm-stat-icon" style="background:#fff1f2;">
                <i class="ph ph-shield-check" style="font-size:26px; color:#f43f5e;"></i>
            </div>
            <div>
                <div class="sktm-stat-value" style="color:#f43f5e;">{{ number_format($stats['total_sktm']) }}</div>
                <div class="sktm-stat-label">SKTM Terverifikasi</div>
            </div>
        </div>
        <div class="sktm-stat">
            <div class="sktm-stat-icon" style="background:#fffbeb;">
                <i class="ph ph-clock" style="font-size:26px; color:#f59e0b;"></i>
            </div>
            <div>
                <div class="sktm-stat-value" style="color:#f59e0b;">{{ number_format($stats['pending']) }}</div>
                <div class="sktm-stat-label">Menunggu Verifikasi</div>
            </div>
        </div>
        <div class="sktm-stat">
            <div class="sktm-stat-icon" style="background:#fef2f2;">
                <i class="ph ph-x-circle" style="font-size:26px; color:#ef4444;"></i>
            </div>
            <div>
                <div class="sktm-stat-value" style="color:#ef4444;">{{ number_format($stats['rejected']) }}</div>
                <div class="sktm-stat-label">Ditolak</div>
            </div>
        </div>
        <div class="sktm-stat">
            <div class="sktm-stat-icon" style="background:#f0fdf4;">
                <i class="ph ph-chart-pie" style="font-size:26px; color:#10b981;"></i>
            </div>
            <div>
                @php
                    $totalSubmitted = $stats['total_sktm'] + $stats['pending'] + $stats['rejected'];
                    $pct = $totalSubmitted > 0 ? round(($stats['total_sktm'] / $totalSubmitted) * 100) : 0;
                @endphp
                <div class="sktm-stat-value" style="color:#10b981;">{{ $pct }}%</div>
                <div class="sktm-stat-label">Tingkat Persetujuan</div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Per Lembaga --}}
    <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
        <i class="ph ph-buildings" style="color:#f43f5e; font-size:20px;"></i>
        Rekap Per Lembaga
    </div>
    <div class="lembaga-grid" style="margin-bottom:28px;">
        @foreach($lembagaList as $lem)
        <div class="lembaga-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div>
                    <div style="font-weight:700; font-size:13px; color:#1e293b;">{{ $lem->nama }}</div>
                    <div style="font-size:11px; color:#0891b2; font-weight:600; margin-top:2px;">{{ $lem->jenis_label }}</div>
                </div>
                <div style="background:#fff1f2; border-radius:10px; padding:6px 12px; text-align:center;">
                    <div style="font-size:1.25rem; font-weight:800; color:#f43f5e; font-variant-numeric:tabular-nums;">{{ $lem->sktm_approved }}</div>
                    <div style="font-size:10px; color:#94a3b8; font-weight:600;">Terverifikasi</div>
                </div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                @if($lem->sktm_pending > 0)
                    <span style="background:#fef3c7; color:#d97706; font-size:11px; font-weight:700; padding:2px 8px; border-radius:8px; display:inline-flex; align-items:center; gap:4px;">
                        <i class="ph ph-clock" style="font-size:12px;"></i> {{ $lem->sktm_pending }} Pending
                    </span>
                @endif
                @if($lem->sktm_rejected > 0)
                    <span style="background:#fee2e2; color:#dc2626; font-size:11px; font-weight:700; padding:2px 8px; border-radius:8px; display:inline-flex; align-items:center; gap:4px;">
                        <i class="ph ph-x-circle" style="font-size:12px;"></i> {{ $lem->sktm_rejected }} Ditolak
                    </span>
                @endif
                @if($lem->sktm_approved == 0 && $lem->sktm_pending == 0 && $lem->sktm_rejected == 0)
                    <span style="color:#94a3b8; font-size:12px; font-style:italic;">Belum ada pengajuan</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter + Tabel Daftar Siswa SKTM --}}
    <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
        <i class="ph ph-list-checks" style="color:#f43f5e; font-size:20px;"></i>
        Daftar Siswa SKTM
    </div>

    <div class="card" style="padding:16px 20px; margin-bottom:16px;">
        <form method="GET" action="{{ route('super-admin.sktm.rekap') }}" style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end;">

            <div style="flex:1; min-width:180px;">
                <label class="form-label" style="font-size:11px;">Cari Siswa</label>
                <div style="position:relative;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:15px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Nama, NISN, NIK..." value="{{ request('search') }}" style="padding-left:36px;">
                </div>
            </div>

            <div style="width:160px;">
                <label class="form-label" style="font-size:11px;">Lembaga</label>
                <select name="lembaga_id" class="form-select">
                    <option value="">Semua Lembaga</option>
                    @foreach($allLembaga as $lem)
                        <option value="{{ $lem->id }}" {{ request('lembaga_id') == $lem->id ? 'selected' : '' }}>
                            {{ $lem->jenis_label }} — {{ $lem->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="width:160px;">
                <label class="form-label" style="font-size:11px;">Status SKTM</label>
                <select name="status_sktm" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="approved" {{ request('status_sktm') == 'approved' ? 'selected' : '' }}>✅ Terverifikasi</option>
                    <option value="pending" {{ request('status_sktm') == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                    <option value="rejected" {{ request('status_sktm') == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>

            <div style="width:140px;">
                <label class="form-label" style="font-size:11px;">Program</label>
                <select name="program" class="form-select">
                    <option value="">Semua Program</option>
                    @foreach(\App\Models\Siswa::PROGRAM_LIST as $val => $label)
                        <option value="{{ $val }}" {{ request('program') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary" style="height:41px; padding:0 20px;">
                    <i class="ph ph-funnel" style="font-size:15px;"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'lembaga_id', 'status_sktm', 'program']))
                    <a href="{{ route('super-admin.sktm.rekap') }}" class="btn btn-secondary" style="height:41px; display:inline-flex; align-items:center; padding:0 16px;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Siswa</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Lembaga</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Kelas / Program</th>
                    <th style="padding:12px 16px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Status SKTM</th>
                    <th style="padding:12px 16px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Berkas</th>
                    <th style="padding:12px 20px; text-align:right; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @if($siswaList->isEmpty())
                    <tr>
                        <td colspan="6" style="padding:48px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-shield-slash" style="font-size:44px; display:block; margin-bottom:8px;"></i>
                            Tidak ada data siswa SKTM ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach($siswaList as $sis)
                    @php
                        $sktmColors = [
                            'approved' => ['bg' => '#d1fae5', 'color' => '#059669', 'label' => '✅ Terverifikasi'],
                            'pending'  => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => '⏳ Menunggu'],
                            'rejected' => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => '❌ Ditolak'],
                        ][$sis->status_sktm] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => $sis->status_sktm];
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                        <td style="padding:14px 20px;">
                            <div style="font-weight:700; font-size:13.5px; color:#1e293b;">{{ $sis->nama }}</div>
                            <div style="font-size:11px; color:#94a3b8; font-family:monospace; margin-top:2px;">NISN: {{ $sis->nis ?? '—' }}</div>
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:10px; font-weight:700; color:#0891b2; background:rgba(8,145,178,0.1); padding:2px 6px; border-radius:8px; text-transform:uppercase;">
                                {{ $sis->lembaga?->jenis_label }}
                            </span>
                            <div style="font-size:12px; color:#334155; margin-top:4px; font-weight:500;">{{ $sis->lembaga?->nama }}</div>
                        </td>
                        <td style="padding:14px 16px;">
                            <div style="font-weight:600; font-size:13px; color:#1e293b;">{{ $sis->kelas ?? '—' }}</div>
                            <div style="font-size:11px; color:#10b981; font-weight:700; margin-top:2px;">{{ $sis->program_label }}</div>
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="font-size:11px; font-weight:700; background:{{ $sktmColors['bg'] }}; color:{{ $sktmColors['color'] }}; padding:3px 10px; border-radius:10px; display:inline-block;">
                                {{ $sktmColors['label'] }}
                            </span>
                            @if($sis->status_sktm === 'rejected' && $sis->keterangan_sktm)
                                <div style="font-size:11px; color:#94a3b8; margin-top:4px; max-width:180px; margin-inline:auto; line-height:1.4;">{{ $sis->keterangan_sktm }}</div>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            @if($sis->dokumen_sktm)
                                <a href="{{ route('super-admin.sktm.berkas', $sis->id) }}" target="_blank"
                                   style="display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:700; color:#0891b2; text-decoration:none; background:rgba(8,145,178,0.08); padding:4px 10px; border-radius:8px; border:1px solid rgba(8,145,178,0.2);">
                                    <i class="ph ph-file-pdf" style="font-size:14px;"></i> Buka
                                </a>
                            @else
                                <span style="font-size:11px; color:#cbd5e1; font-style:italic;">—</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px; text-align:right; font-size:12px; color:#64748b;">
                            {{ $sis->updated_at?->format('d/m/Y') ?? '—' }}
                            <div style="font-size:10.5px; color:#94a3b8; margin-top:2px;">{{ $sis->updated_at?->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($siswaList->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $siswaList->links() }}
        </div>
    @endif

</div>
@endsection
