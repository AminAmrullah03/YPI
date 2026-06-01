@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

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
    .badge-aktif {
        background: #ecfdf5; color: #059669; border: 1px solid rgba(16,185,129,0.15);
        font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; text-transform: uppercase;
    }
</style>

<div class="dash-root">

    {{-- ── Welcome Banner ── --}}
    <div class="welcome-banner">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; position:relative; z-index:1;">
            <div>
                <h1 class="welcome-title">أهلاً وسهلاً 👋</h1>
                <p class="welcome-sub">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> — Yayasan Pendidikan Islam PP Darus Sholah</p>
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

    {{-- ── Section Heading ── --}}
    <div class="section-title">
        <div class="section-title-icon" style="background:#f0fdf4;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="#10b981" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
            </svg>
        </div>
        <h2>Statistik Terpadu</h2>
        <span class="section-badge">Realtime</span>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stat-cards-wrap">

        {{-- Total Siswa Aktif --}}
        <div class="stat-card stat-green">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12.5V17c0 3 3 3.5 6 3.5s6-.5 6-3.5v-4.5"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Total Siswa Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['total_siswa_aktif']) }}</div>
                <div class="stat-card-sub">dari {{ $stats['total_siswa'] }} terdaftar</div>
            </div>
        </div>

        {{-- Total Guru Aktif --}}
        <div class="stat-card stat-blue">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19h16M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zM12 9v4M9 11h6"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Total Guru Aktif</div>
                <div class="stat-card-value">{{ number_format($stats['total_guru_aktif']) }}</div>
                <div class="stat-card-sub">dari {{ $stats['total_guru'] }} terdaftar</div>
            </div>
        </div>

        {{-- Total Lembaga --}}
        <div class="stat-card stat-violet">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18M3 10h18M5 6h14a2 2 0 0 1 2 2v13H3V8a2 2 0 0 1 2-2zM9 14h6m-6 4h6"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Lembaga Aktif</div>
                <div class="stat-card-value">{{ $stats['total_lembaga'] }}</div>
                <div class="stat-card-sub">TPQ, KB/TK, SD, SMP, SMA, MA</div>
            </div>
        </div>

        {{-- Total Anggota --}}
        <div class="stat-card stat-amber">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Total Anggota</div>
                <div class="stat-card-value">{{ number_format($stats['total_siswa'] + $stats['total_guru']) }}</div>
                <div class="stat-card-sub">Siswa + Guru Terdaftar</div>
            </div>
        </div>

        {{-- Siswa Kurang Mampu (SKTM) --}}
        <a href="{{ route('super-admin.sktm.rekap') }}" style="text-decoration:none;">
        <div class="stat-card" style="--stat-color:#f43f5e; --stat-icon-bg:#fff1f2; --stat-bg:rgba(244,63,94,0.05); border-color:transparent; cursor:pointer;">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="color:#f43f5e;">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div>
                <div class="stat-card-label">Siswa Kurang Mampu</div>
                <div class="stat-card-value" style="color:#f43f5e;">{{ number_format($stats['sktm_approved']) }}</div>
                <div class="stat-card-sub" style="display:flex; align-items:center; gap:6px;">
                    SKTM Terverifikasi
                    @if($stats['sktm_pending'] > 0)
                        <span style="background:#fef3c7; color:#d97706; font-size:10px; font-weight:700; padding:1px 6px; border-radius:8px;">{{ $stats['sktm_pending'] }} pending</span>
                    @endif
                </div>
            </div>
        </div>
        </a>

    </div>

    {{-- ── Chart + Lembaga Summary Grid ── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(380px, 1fr)); gap:20px; margin-bottom:28px;">

        {{-- Bar Chart --}}
        <div class="dash-card">
            <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <svg style="width:18px;height:18px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 20V10M12 20V4M6 20v-6"/>
                </svg>
                Perbandingan Data per Lembaga
            </div>
            <div id="chart-lembaga" style="min-height:280px;"></div>
        </div>

        {{-- Tabel Ringkasan --}}
        <div class="dash-card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                <svg style="width:18px;height:18px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Ringkasan per Lembaga
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="padding:10px 20px;">Lembaga</th>
                        <th style="padding:10px 16px; text-align:center;">Siswa</th>
                        <th style="padding:10px 16px; text-align:center;">Guru</th>
                        <th style="padding:10px 16px; text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lembagaList as $lem)
                    <tr>
                        <td style="padding:12px 20px;">
                            <div style="font-weight:600; font-size:13px; color:#1e293b;">{{ $lem->nama }}</div>
                            <div style="font-size:11px; color:#94a3b8;">{{ $lem->jenis_label }}</div>
                        </td>
                        <td style="padding:12px 16px; text-align:center; font-size:13px; font-weight:600; color:#10b981;">
                            {{ $lem->siswa->where('status', 'aktif')->count() }}
                        </td>
                        <td style="padding:12px 16px; text-align:center; font-size:13px; font-weight:600; color:#0891b2;">
                            {{ $lem->guru->where('status', 'aktif')->count() }}
                        </td>
                        <td style="padding:12px 16px; text-align:center;">
                            <span class="badge-aktif">Aktif</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- ── Aktivitas Terbaru (Audit Log) ── --}}
    <div class="dash-card" style="padding:0; overflow:hidden; margin-bottom: 28px;">
        <div style="padding:20px 24px 12px; font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
            <svg style="width:18px;height:18px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Aktivitas Terbaru
        </div>

        @if($recentActivities->isEmpty())
            <div style="padding:32px; text-align:center; color:#94a3b8; font-size:14px;">
                Belum ada aktivitas
            </div>
        @else
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="padding:10px 20px;">Pengguna</th>
                        <th style="padding:10px 16px;">Aksi</th>
                        <th style="padding:10px 16px;">Keterangan</th>
                        <th style="padding:10px 20px; text-align:right;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $log)
                    <tr>
                        <td style="padding:12px 20px; font-size:13px; font-weight:600; color:#1e293b;">
                            {{ $log->user?->name ?? 'System' }}
                        </td>
                        <td style="padding:12px 16px;">
                            @php
                                $actionColors = ['create'=>'#10b981','update'=>'#f59e0b','delete'=>'#ef4444'];
                                $color = $actionColors[$log->action] ?? '#94a3b8';
                            @endphp
                            <span style="font-size:11px; font-weight:700; color:{{ $color }}; background:{{ $color }}1a; padding:2px 8px; border-radius:12px; text-transform:uppercase; border: 1px solid {{ $color }}2a;">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">{{ $log->description ?? $log->model }}</td>
                        <td style="padding:12px 20px; font-size:12px; color:#94a3b8; text-align:right;">
                            {{ $log->created_at?->diffForHumans() ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var options = {
        series: [
            {
                name: 'Siswa Aktif',
                data: @json($chartData['siswa_aktif'])
            },
            {
                name: 'Guru Aktif',
                data: @json($chartData['guru_aktif'])
            }
        ],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        colors: ['#10b981', '#0891b2'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '55%',
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($chartData['labels']),
            labels: { style: { fontSize: '12px', fontWeight: 500, colors: '#64748b' } }
        },
        yaxis: {
            labels: { style: { fontSize: '12px', colors: '#64748b' } }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
        },
        legend: {
            position: 'top',
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            fontSize: '12px',
        },
        tooltip: {
            theme: 'light',
            style: { fontFamily: 'Plus Jakarta Sans, sans-serif' }
        }
    };

    var chart = new ApexCharts(document.getElementById('chart-lembaga'), options);
    chart.render();
});
</script>
@endpush
