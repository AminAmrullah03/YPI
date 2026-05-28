@extends('layouts.app')

@section('title', 'Dashboard Admin Lembaga')

@section('content')
<style>
    .dash-root { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Welcome Banner ── */
    .welcome-banner {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, #064e3b 0%, #065f46 35%, #047857 65%, #059669 100%);
        border-radius: 20px; padding: 28px 32px;
        margin-bottom: 28px;
        box-shadow: 0 8px 32px rgba(4,120,87,0.3), 0 0 0 1px rgba(16,185,129,0.15);
    }
    .welcome-banner::before {
        content: ''; position: absolute; top: -80px; right: -60px;
        width: 260px; height: 260px; border-radius: 50%;
        background: rgba(16,185,129,0.1); pointer-events: none;
    }
    .welcome-banner::after {
        content: ''; position: absolute; bottom: -50px; left: 35%;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(4,78,59,0.35); pointer-events: none;
    }
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0 0 4px; position: relative; z-index:1; }
    .welcome-sub   { color: #6ee7b7; font-size: 0.875rem; margin: 0; position: relative; z-index:1; font-weight:500; }
    .welcome-chip {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(0,0,0,0.18); backdrop-filter: blur(8px);
        border: 1px solid rgba(16,185,129,0.3); border-radius: 12px;
        padding: 10px 16px; color: #fff; position: relative; z-index:1;
    }
    .welcome-chip-label { font-size: 0.65rem; color: #6ee7b7; font-weight:600; letter-spacing:0.04em; text-transform:uppercase; }
    .welcome-chip-val   { font-size: 0.825rem; font-weight: 700; margin-top:2px; }

    /* ── Section heading ── */
    .section-title {
        display: flex; align-items: center; gap: 10px;
        margin: 0 0 14px;
        margin-top: 24px;
    }
    .section-title-icon {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .section-title h2 {
        font-size: 0.9375rem; font-weight: 700;
        color: #0f172a; margin: 0; letter-spacing: -0.01em;
    }
    .section-title .section-badge {
        font-size: 0.65rem; font-weight: 700;
        padding: 2px 8px; border-radius: 999px;
        background: #f0fdf4; color: #059669;
        border: 1px solid #d1fae5;
        text-transform: uppercase; letter-spacing: 0.06em;
    }

    /* ── STAT CARDS ── */
    .stat-cards-wrap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff; border-radius: 16px;
        padding: 20px;
        border: 1.5px solid transparent;
        position: relative; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.04);
        transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border-color: var(--stat-color);
    }
    .stat-card::before {
        content: ''; position: absolute;
        bottom: 0; right: 0;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: var(--stat-bg);
        transform: translate(20px, 20px);
        transition: transform 0.3s;
    }
    .stat-card:hover::before { transform: translate(10px, 10px); }
    .stat-card-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: var(--stat-icon-bg);
        margin-bottom: 14px;
        position: relative; z-index: 1;
        flex-shrink: 0;
    }
    .stat-card-icon svg { color: var(--stat-color); width: 22px; height: 22px; }
    .stat-card-label {
        font-size: 0.75rem; font-weight: 600;
        color: #94a3b8; text-transform: uppercase;
        letter-spacing: 0.06em; margin-bottom: 6px;
        position: relative; z-index: 1;
    }
    .stat-card-value {
        font-size: 2rem; font-weight: 800;
        color: #0f172a; line-height: 1;
        position: relative; z-index: 1;
        font-variant-numeric: tabular-nums;
    }
    .stat-card-sub {
        font-size: 0.72rem; color: #94a3b8; font-weight: 500;
        margin-top: 6px; position: relative; z-index: 1;
    }
    /* Stat color variants */
    .stat-green  { --stat-color:#10b981; --stat-icon-bg:#ecfdf5; --stat-bg:rgba(16,185,129,0.05); }
    .stat-blue   { --stat-color:#3b82f6; --stat-icon-bg:#eff6ff; --stat-bg:rgba(59,130,246,0.05); }
    .stat-violet { --stat-color:#8b5cf6; --stat-icon-bg:#f5f3ff; --stat-bg:rgba(139,92,246,0.05); }
    .stat-amber  { --stat-color:#f59e0b; --stat-icon-bg:#fffbeb; --stat-bg:rgba(245,158,11,0.05); }

    /* ── QUICK ACTION CARDS ── */
    .action-cards-wrap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .action-card {
        display: flex; align-items: center; gap: 14px;
        padding: 18px 20px;
        background: #fff; border-radius: 14px;
        text-decoration: none;
        border: 1.5px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        position: relative; overflow: hidden;
    }
    .action-card::after {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 3px;
        background: var(--ac-color);
        opacity: 0; transition: opacity 0.2s;
        border-radius: 14px 14px 0 0;
    }
    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--ac-border);
    }
    .action-card:hover::after { opacity: 1; }
    .action-card-icon {
        width: 48px; height: 48px; border-radius: 14px;
        background: var(--ac-icon-bg);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    .action-card:hover .action-card-icon { transform: scale(1.08); }
    .action-card-icon svg { color: var(--ac-color); width: 24px; height: 24px; }
    .action-card-label { font-size: 0.875rem; font-weight: 700; color: #1e293b; }
    .action-card-desc  { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-weight: 500; }
    .action-card-arrow {
        margin-left: auto; color: #e2e8f0;
        transition: color 0.2s, transform 0.2s;
    }
    .action-card:hover .action-card-arrow { color: var(--ac-color); transform: translateX(3px); }
    /* Action card variants */
    .ac-emerald { --ac-color:#10b981; --ac-border:rgba(16,185,129,0.3); --ac-icon-bg:#ecfdf5; }
    .ac-blue    { --ac-color:#3b82f6; --ac-border:rgba(59,130,246,0.3);  --ac-icon-bg:#eff6ff; }
    .ac-teal    { --ac-color:#0891b2; --ac-border:rgba(8,145,178,0.3);   --ac-icon-bg:#ecfeff; }
    .ac-violet  { --ac-color:#8b5cf6; --ac-border:rgba(139,92,246,0.3);  --ac-icon-bg:#f5f3ff; }

    /* ── CARDS & TABLES ── */
    .dash-card {
        background: #fff; border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03);
        border: 1.5px solid #f1f5f9;
    }
    .dash-table {
        width: 100%; border-collapse: collapse;
    }
    .dash-table th {
        padding: 10px 20px; text-align: left; font-size: 11px;
        font-weight: 600; color: #94a3b8; text-transform: uppercase;
        letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .dash-table td {
        padding: 12px 20px; font-size: 13.5px; color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }
    .badge {
        font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; text-transform: uppercase;
        display: inline-block;
        border: 1px solid transparent;
    }
    .badge-aktif {
        background: #ecfdf5; color: #059669; border-color: rgba(16,185,129,0.15);
    }
    .badge-tidak_aktif {
        background: #fef2f2; color: #ef4444; border-color: rgba(239,68,68,0.15);
    }
    .badge-lulus {
        background: #eff6ff; color: #2563eb; border-color: rgba(37,99,235,0.15);
    }
    .badge-pindah {
        background: #fffbeb; color: #d97706; border-color: rgba(217,119,6,0.15);
    }
    .badge-keluar {
        background: #f1f5f9; color: #475569; border-color: rgba(71,85,105,0.15);
    }
    .badge-pensiun {
        background: #faf5ff; color: #7c3aed; border-color: rgba(124,58,237,0.15);
    }
</style>

<div class="dash-root">

    {{-- ── Welcome Banner ── --}}
    <div class="welcome-banner">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; position:relative; z-index:1;">
            <div>
                <h1 class="welcome-title">أهلاً وسهلاً 👋</h1>
                <p class="welcome-sub">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> — Admin {{ $lembaga?->nama ?? 'Lembaga' }}</p>
            </div>
            <div class="welcome-chip" style="flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6ee7b7" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <div>
                    <div class="welcome-chip-label">Hari ini</div>
                    <div class="welcome-chip-val">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stat-cards-wrap">

        {{-- Siswa Aktif --}}
        <div class="stat-card stat-green">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12.5V17c0 3 3 3.5 6 3.5s6-.5 6-3.5v-4.5"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Siswa Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['siswa_aktif']) }}</div>
                <div class="stat-card-sub">dari {{ $stats['siswa_total'] }} terdaftar</div>
            </div>
        </div>

        {{-- Guru Aktif --}}
        <div class="stat-card stat-blue">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19h16M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zM12 9v4M9 11h6"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Guru Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['guru_aktif']) }}</div>
                <div class="stat-card-sub">dari {{ $stats['guru_total'] }} terdaftar</div>
            </div>
        </div>

        {{-- Siswa Tidak Aktif --}}
        <div class="stat-card stat-amber">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Siswa Non-Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['siswa_tidak_aktif']) }}</div>
                <div class="stat-card-sub">perlu peninjauan status</div>
            </div>
        </div>

        {{-- Guru Non-Aktif --}}
        <div class="stat-card stat-violet">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Guru Non-Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['guru_keluar']) }}</div>
                <div class="stat-card-sub">pensiun / keluar / dll</div>
            </div>
        </div>

    </div>

    {{-- ── Quick Actions ── --}}
    <div class="section-title">
        <div class="section-title-icon" style="background:#eff6ff;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.75" style="width:16px;height:16px;">
                <path d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
            </svg>
        </div>
        <h2>Menu Pintasan / Akses Cepat</h2>
    </div>

    <div class="action-cards-wrap">
        <a href="{{ route('admin.siswa.create') }}" class="action-card ac-emerald">
            <div class="action-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
            </div>
            <div>
                <div class="action-card-label">Siswa Baru</div>
                <div class="action-card-desc">Tambah data siswa manual</div>
            </div>
            <svg class="action-card-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.siswa.import-form') }}" class="action-card ac-blue">
            <div class="action-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <div class="action-card-label">Import Siswa</div>
                <div class="action-card-desc">Import data siswa dari Excel</div>
            </div>
            <svg class="action-card-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.guru.create') }}" class="action-card ac-teal">
            <div class="action-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
            </div>
            <div>
                <div class="action-card-label">Guru Baru</div>
                <div class="action-card-desc">Tambah data guru manual</div>
            </div>
            <svg class="action-card-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.laporan.index') }}" class="action-card ac-violet">
            <div class="action-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M3 3v18h18M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                </svg>
            </div>
            <div>
                <div class="action-card-label">Laporan Lembaga</div>
                <div class="action-card-desc">Cetak laporan & export data</div>
            </div>
            <svg class="action-card-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>
    </div>

    {{-- ── Chart & Summary Grid ── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px; margin-bottom:28px;">
        
        {{-- Donut Chart --}}
        <div class="dash-card" style="display:flex; flex-direction:column; justify-content:space-between;">
            <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                <svg style="width:18px;height:18px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83M22 12A10 10 0 0 0 12 2v10z"/>
                </svg>
                Distribusi Status Siswa
            </div>
            
            @if(empty($statusSiswa))
                <div style="padding:40px 20px; text-align:center; color:#94a3b8; font-size:13.5px; flex:1; display:flex; align-items:center; justify-content:center;">
                    Belum ada data siswa untuk grafik.
                </div>
            @else
                <div id="chart-status-siswa" style="min-height:220px;"></div>
            @endif
        </div>

        {{-- Lembaga Info Card --}}
        <div class="dash-card" style="display:flex; flex-direction:column; justify-content:center; gap:16px;">
            <h3 style="margin:0; font-size:18px; font-weight:800; color:#1e293b;">Informasi Lembaga</h3>
            <div style="font-size:14px; color:#475569; display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; border-bottom: 1px solid #f1f5f9; padding-bottom:8px;">
                    <span style="font-weight:600; color:#64748b;">Nama Lembaga</span>
                    <span>{{ $lembaga?->nama }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; border-bottom: 1px solid #f1f5f9; padding-bottom:8px;">
                    <span style="font-weight:600; color:#64748b;">Jenis / Tingkat</span>
                    <span class="badge-aktif" style="border:none;">{{ $lembaga?->jenis_label }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; border-bottom: 1px solid #f1f5f9; padding-bottom:8px;">
                    <span style="font-weight:600; color:#64748b;">Kepala Sekolah</span>
                    <span>{{ $lembaga?->kepala ?? '—' }}</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="font-weight:600; color:#64748b;">Alamat</span>
                    <span style="text-align:right; max-width:200px;">{{ $lembaga?->alamat ?? '—' }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Siswa & Guru Tables Grid ── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(380px, 1fr)); gap:20px; margin-bottom: 28px;">
        
        {{-- Siswa Terbaru --}}
        <div class="dash-card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg style="width:18px;height:18px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Siswa Baru Ditambahkan
                </div>
                <a href="{{ route('admin.siswa.index') }}" style="font-size:12px; font-weight:600; color:#10b981; text-decoration:none;">Lihat Semua</a>
            </div>
            
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="padding:10px 20px;">Siswa</th>
                        <th style="padding:10px 16px;">Kelas</th>
                        <th style="padding:10px 20px; text-align:center; width:100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($siswaRecent->isEmpty())
                        <tr>
                            <td colspan="3" style="padding:32px; text-align:center; color:#94a3b8; font-size:13px;">
                                Belum ada data siswa
                            </td>
                        </tr>
                    @else
                        @foreach($siswaRecent as $sis)
                        <tr>
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

        {{-- Guru Terbaru --}}
        <div class="dash-card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg style="width:18px;height:18px;color:#0891b2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Guru Baru Ditambahkan
                </div>
                <a href="{{ route('admin.guru.index') }}" style="font-size:12px; font-weight:600; color:#0891b2; text-decoration:none;">Lihat Semua</a>
            </div>
            
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="padding:10px 20px;">Nama Guru</th>
                        <th style="padding:10px 16px;">Jabatan</th>
                        <th style="padding:10px 20px; text-align:center; width:100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($guruRecent->isEmpty())
                        <tr>
                            <td colspan="3" style="padding:32px; text-align:center; color:#94a3b8; font-size:13px;">
                                Belum ada data guru
                            </td>
                        </tr>
                    @else
                        @foreach($guruRecent as $gur)
                        <tr>
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

@push('scripts')
@if(!empty($statusSiswa))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawData = @json($statusSiswa);
    const labels = [];
    const series = [];
    
    const statusLabels = {
        'aktif': 'Aktif',
        'tidak_aktif': 'Tidak Aktif',
        'lulus': 'Lulus',
        'pindah': 'Pindah'
    };

    Object.keys(rawData).forEach(key => {
        labels.push(statusLabels[key] || key);
        series.push(rawData[key]);
    });

    var options = {
        series: series,
        labels: labels,
        chart: {
            type: 'donut',
            height: 220,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        colors: ['#10b981', '#ef4444', '#0891b2', '#f59e0b'],
        legend: {
            position: 'bottom',
            fontSize: '12px',
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex];
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + ' Siswa';
                }
            }
        }
    };

    var chart = new ApexCharts(document.getElementById('chart-status-siswa'), options);
    chart.render();
});
</script>
@endif
@endpush
