{{-- ============================================================
     SIDEBAR — DIGIDAS YPI (Desktop reference)
     Dark Navy + Emerald, Collapsible, Animated
     ============================================================ --}}

@php
    $user = auth()->user();
    $isSuperAdmin = $user->isSuperAdmin();
@endphp

<style>
    /* ── Sidebar root ── */
    :root {
        --sidebar-w: 260px;
        --sidebar-w-collapsed: 68px;
        --navy-900: #0a1628;
        --navy-800: #0f1f3d;
        --navy-700: #162447;
        --navy-600: #1e3a5f;
        --emerald-500: #10b981;
        --emerald-400: #34d399;
        --emerald-300: #6ee7b7;
        --sidebar-transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar {
        position: fixed;
        top: 0; left: 0; bottom: 0;
        width: var(--sidebar-w);
        background: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-800) 60%, #0c1a35 100%);
        border-right: 1px solid rgba(16,185,129,0.12);
        box-shadow: 4px 0 24px rgba(0,0,0,0.35);
        display: flex;
        flex-direction: column;
        z-index: 1000;
        transition: width var(--sidebar-transition);
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }
    .sidebar.collapsed { width: var(--sidebar-w-collapsed); }

    /* Pattern overlay */
    .sidebar::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2310b981' fill-opacity='0.025'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        opacity: 0.6;
    }

    /* ── Logo area ── */
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 18px 16px;
        border-bottom: 1px solid rgba(16,185,129,0.1);
        text-decoration: none;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        min-height: 68px;
        overflow: hidden;
        transition: padding var(--sidebar-transition), justify-content var(--sidebar-transition);
    }
    .sidebar.collapsed .sidebar-logo {
        justify-content: center;
        padding: 16px 0;
        gap: 0;
    }
    .sidebar-logo-img {
        width: 36px; height: 36px;
        object-fit: contain;
        flex-shrink: 0;
        filter: drop-shadow(0 0 8px rgba(16,185,129,0.5));
        transition: filter 0.2s;
    }
    .sidebar-logo:hover .sidebar-logo-img { filter: drop-shadow(0 0 14px rgba(16,185,129,0.8)); }
    .sidebar-logo-text-wrap {
        overflow: hidden;
        transition: opacity var(--sidebar-transition), max-width var(--sidebar-transition), transform var(--sidebar-transition);
        max-width: 200px;
        opacity: 1;
        transform: translateX(0);
        white-space: nowrap;
    }
    .sidebar.collapsed .sidebar-logo-text-wrap {
        opacity: 0;
        max-width: 0;
        transform: translateX(-8px);
        pointer-events: none;
    }
    .sidebar-logo-text {
        font-size: 1rem; font-weight: 800;
        color: #f1f5f9; letter-spacing: -0.02em;
        white-space: nowrap;
    }
    .sidebar-logo-text span { color: var(--emerald-500); }
    .sidebar-logo-sub {
        font-size: 0.6rem; color: rgba(110,231,183,0.6);
        font-weight: 500; letter-spacing: 0.06em;
        text-transform: uppercase; margin-top: 2px;
        white-space: nowrap;
    }

    /* ── Nav scroll area ── */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px 0 8px;
        position: relative;
        z-index: 1;
    }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.2); border-radius: 99px; }

    /* ── Section ── */
    .nav-section { margin-bottom: 4px; }
    .nav-section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px 4px;
        font-size: 0.6rem;
        font-weight: 700;
        color: rgba(100,116,139,0.7);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        white-space: nowrap;
        cursor: pointer;
        transition: color 0.15s;
        user-select: none;
    }
    .nav-section-label:hover { color: rgba(148,163,184,0.9); }
    .nav-section-label-line {
        flex: 1;
        height: 1px;
        background: rgba(16,185,129,0.08);
        transition: opacity var(--sidebar-transition);
    }
    .sidebar.collapsed .nav-section-label-text,
    .sidebar.collapsed .nav-section-label-line,
    .sidebar.collapsed .nav-section-chevron { opacity: 0; width: 0; overflow: hidden; }
    .nav-section-chevron {
        width: 12px; height: 12px;
        color: rgba(100,116,139,0.5);
        transition: transform 0.2s, opacity var(--sidebar-transition);
        flex-shrink: 0;
    }
    .nav-section.open .nav-section-chevron { transform: rotate(0deg); }
    .nav-section:not(.open) .nav-section-chevron { transform: rotate(-90deg); }

    /* Items container — collapsible */
    .nav-items {
        overflow: hidden;
        max-height: 1000px;
        transition: max-height 0.3s ease;
    }
    .nav-section:not(.open) .nav-items { max-height: 0; }
    .sidebar.collapsed .nav-items { max-height: 1000px !important; } /* always show in collapsed */

    /* ── Nav Item ── */
    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 16px;
        margin: 1px 8px;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #7d9ab5;
        text-decoration: none;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        white-space: nowrap;
    }
    .nav-item:hover {
        color: #e2e8f0;
        background: rgba(16,185,129,0.08);
    }
    .nav-item.active {
        color: #fff;
        background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.12));
        box-shadow: 0 0 0 1px rgba(16,185,129,0.25), inset 0 1px 0 rgba(255,255,255,0.05);
        font-weight: 600;
    }
    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 20%; bottom: 20%;
        width: 3px;
        background: var(--emerald-500);
        border-radius: 0 3px 3px 0;
        box-shadow: 0 0 8px rgba(16,185,129,0.6);
    }
    .nav-item-icon {
        width: 18px; height: 18px;
        flex-shrink: 0;
        opacity: 0.7;
        transition: opacity 0.18s, transform 0.18s;
        color: currentColor;
    }
    .nav-item:hover .nav-item-icon,
    .nav-item.active .nav-item-icon {
        opacity: 1;
        transform: scale(1.1);
    }
    .nav-item.active .nav-item-icon { color: var(--emerald-400); }
    .nav-item-label {
        transition: opacity var(--sidebar-transition), max-width var(--sidebar-transition), transform var(--sidebar-transition);
        white-space: nowrap;
        overflow: hidden;
        max-width: 200px;
    }
    .sidebar.collapsed .nav-item-label {
        opacity: 0;
        max-width: 0;
        transform: translateX(-6px);
        pointer-events: none;
    }
    .sidebar.collapsed .nav-item {
        margin: 1px 6px;
        justify-content: center;
        padding: 9px 0;
        overflow: visible;
        gap: 0;
    }
    .sidebar.collapsed .nav-item-icon {
        opacity: 0.75;
        flex-shrink: 0;
        display: block;
    }
    .sidebar.collapsed .nav-item:hover .nav-item-icon,
    .sidebar.collapsed .nav-item.active .nav-item-icon {
        opacity: 1;
        transform: scale(1.1);
    }
    .sidebar.collapsed .nav-item.active::before { display: none; }
    .sidebar.collapsed .nav-item.active {
        border-radius: 10px;
        box-shadow: 0 0 0 1px rgba(16,185,129,0.35), inset 0 1px 0 rgba(255,255,255,0.05);
    }

    /* Tooltip on collapsed */
    .sidebar.collapsed .nav-item::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 12px);
        background: #1e293b;
        color: #f1f5f9;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transform: translateX(-4px);
        transition: opacity 0.15s, transform 0.15s;
        border: 1px solid rgba(16,185,129,0.2);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        z-index: 9999;
    }
    .sidebar.collapsed .nav-item:hover::after {
        opacity: 1;
        transform: translateX(0);
    }

    /* ── User section (bottom) ── */
    .sidebar-user {
        flex-shrink: 0;
        border-top: 1px solid rgba(16,185,129,0.1);
        padding: 12px;
        position: relative;
        z-index: 1;
    }
    .sidebar-user-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        transition: background 0.15s;
        cursor: default;
    }
    .sidebar-user-card:hover { background: rgba(16,185,129,0.06); }
    .sidebar-avatar {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--emerald-500), #059669);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 800; color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(16,185,129,0.35);
    }
    .sidebar-user-info {
        flex: 1;
        min-width: 0;
        transition: opacity var(--sidebar-transition), transform var(--sidebar-transition);
    }
    .sidebar-user-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: #f1f5f9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sidebar-user-role {
        font-size: 0.6rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 999px;
        display: inline-block;
        margin-top: 2px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .role-superadmin { background: rgba(167,139,250,0.15); color: #c4b5fd; border: 1px solid rgba(167,139,250,0.25); }
    .role-asatid      { background: rgba(16,185,129,0.12);  color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); }
    .sidebar.collapsed .sidebar-user-info { opacity: 0; transform: translateX(-6px); width: 0; overflow: hidden; }
    .sidebar.collapsed .sidebar-user-card { justify-content: center; }

    /* Mobile overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 999;
        backdrop-filter: blur(2px);
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform var(--sidebar-transition), width var(--sidebar-transition);
            width: var(--sidebar-w) !important;
        }
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        .sidebar-overlay.active { display: block; }
    }
</style>

{{-- Overlay for mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebarMobile()"></div>

<aside class="sidebar" id="appSidebar">

    {{-- ── Logo ── --}}
    <a href="{{ $isSuperAdmin ? route('super-admin.dashboard') : route('admin.dashboard') }}" class="sidebar-logo">
        <img src="{{ asset('images/logo1.png') }}" alt="DIGIDAS" class="sidebar-logo-img">
        <div class="sidebar-logo-text-wrap">
            <div class="sidebar-logo-text">DIGI<span>DAS</span></div>
            <div class="sidebar-logo-sub">YPI Darus Sholah</div>
        </div>
    </a>

    {{-- ── Navigation ── --}}
    <nav class="sidebar-nav" id="sidebarNav">

        {{-- ── DASHBOARD ── --}}
        <div class="nav-section open" id="section-dashboard">
            <div class="nav-items">
                <a href="{{ $isSuperAdmin ? route('super-admin.dashboard') : route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('super-admin.dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                    <span class="nav-item-label">Dashboard</span>
                </a>
            </div>
        </div>

        @if($isSuperAdmin)
            {{-- ── SUPER ADMIN SECTIONS ── --}}
            
            {{-- ── MANAJEMEN ── --}}
            <div class="nav-section open" id="section-manajemen">
                <div class="nav-section-label" onclick="toggleSection('section-manajemen')">
                    <span class="nav-section-label-text">Manajemen</span>
                    <div class="nav-section-label-line"></div>
                    <svg class="nav-section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="nav-items">
                    <a href="{{ route('super-admin.lembaga.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.lembaga.*') ? 'active' : '' }}"
                       data-tooltip="Lembaga">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18M3 10h18M5 6h14a2 2 0 0 1 2 2v13H3V8a2 2 0 0 1 2-2zM9 14h6m-6 4h6"/>
                        </svg>
                        <span class="nav-item-label">Lembaga</span>
                    </a>
                    <a href="{{ route('super-admin.users.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}"
                       data-tooltip="Pengguna">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                        <span class="nav-item-label">Pengguna</span>
                    </a>
                </div>
            </div>

            {{-- ── DATA YAYASAN ── --}}
            <div class="nav-section open" id="section-data">
                <div class="nav-section-label" onclick="toggleSection('section-data')">
                    <span class="nav-section-label-text">Data Yayasan</span>
                    <div class="nav-section-label-line"></div>
                    <svg class="nav-section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="nav-items">
                    <a href="{{ route('super-admin.siswa.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.siswa.*') ? 'active' : '' }}"
                       data-tooltip="Data Siswa">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12.5V17c0 3 3 3.5 6 3.5s6-.5 6-3.5v-4.5"/>
                        </svg>
                        <span class="nav-item-label">Data Siswa</span>
                    </a>
                    <a href="{{ route('super-admin.guru.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.guru.*') ? 'active' : '' }}"
                       data-tooltip="Data Guru">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19h16M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zM12 9v4M9 11h6"/>
                        </svg>
                        <span class="nav-item-label">Data Guru</span>
                    </a>
                    @php
                        $pendingSktmCount = \App\Models\Siswa::where('status_sktm', 'pending')->count();
                    @endphp
                    <a href="{{ route('super-admin.sktm.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.sktm.*') ? 'active' : '' }}"
                       data-tooltip="Verifikasi SKTM">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                        </svg>
                        <span class="nav-item-label" style="display:flex; align-items:center; justify-content:space-between; width:100%; flex:1;">
                            <span>Verifikasi SKTM</span>
                            @if($pendingSktmCount > 0)
                                <span class="badge-pending-count" style="background:#ef4444; color:#fff; font-size:9.5px; font-weight:700; padding:1px 5px; border-radius:999px; margin-left:6px; flex-shrink:0; line-height:1.2;">
                                    {{ $pendingSktmCount }}
                                </span>
                            @endif
                        </span>
                    </a>
                    <a href="{{ route('super-admin.sktm.rekap') }}"
                       class="nav-item {{ request()->routeIs('super-admin.sktm.rekap') ? 'active' : '' }}"
                       data-tooltip="Rekap SKTM">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span class="nav-item-label">Rekap SKTM</span>
                    </a>
                </div>
            </div>

            {{-- ── LAPORAN ── --}}
            <div class="nav-section open" id="section-laporan">
                <div class="nav-section-label" onclick="toggleSection('section-laporan')">
                    <span class="nav-section-label-text">Laporan</span>
                    <div class="nav-section-label-line"></div>
                    <svg class="nav-section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="nav-items">
                    <a href="{{ route('super-admin.laporan.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.laporan.*') ? 'active' : '' }}"
                       data-tooltip="Laporan">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                        </svg>
                        <span class="nav-item-label">Laporan</span>
                    </a>
                    <a href="{{ route('super-admin.audit-log.index') }}"
                       class="nav-item {{ request()->routeIs('super-admin.audit-log.*') ? 'active' : '' }}"
                       data-tooltip="Audit Log">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        <span class="nav-item-label">Audit Log</span>
                    </a>
                </div>
            </div>

        @else
            {{-- ── ADMIN LEMBAGA SECTIONS ── --}}
            
            {{-- ── DATA LEMBAGA ── --}}
            <div class="nav-section open" id="section-data-lembaga">
                <div class="nav-section-label" onclick="toggleSection('section-data-lembaga')">
                    <span class="nav-section-label-text">Data Lembaga</span>
                    <div class="nav-section-label-line"></div>
                    <svg class="nav-section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="nav-items">
                    <a href="{{ route('admin.siswa.index') }}"
                       class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"
                       data-tooltip="Data Siswa">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12.5V17c0 3 3 3.5 6 3.5s6-.5 6-3.5v-4.5"/>
                        </svg>
                        <span class="nav-item-label">Data Siswa</span>
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                       class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}"
                       data-tooltip="Data Guru">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19h16M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zM12 9v4M9 11h6"/>
                        </svg>
                        <span class="nav-item-label">Data Guru</span>
                    </a>
                </div>
            </div>

            {{-- ── LAPORAN ── --}}
            <div class="nav-section open" id="section-laporan-lembaga">
                <div class="nav-section-label" onclick="toggleSection('section-laporan-lembaga')">
                    <span class="nav-section-label-text">Laporan</span>
                    <div class="nav-section-label-line"></div>
                    <svg class="nav-section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="nav-items">
                    <a href="{{ route('admin.laporan.index') }}"
                       class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"
                       data-tooltip="Laporan & Export">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                        </svg>
                        <span class="nav-item-label">Laporan & Export</span>
                    </a>
                    @php $adminSktmPending = \App\Models\Siswa::where('lembaga_id', auth()->user()->lembaga_id)->where('status_sktm', 'pending')->count(); @endphp
                    <a href="{{ route('admin.sktm.rekap') }}"
                       class="nav-item {{ request()->routeIs('admin.sktm.rekap') ? 'active' : '' }}"
                       data-tooltip="Rekap SKTM">
                        <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="color:#f43f5e;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span class="nav-item-label" style="display:flex; align-items:center; justify-content:space-between; width:100%; flex:1;">
                            <span>Rekap SKTM</span>
                            @if($adminSktmPending > 0)
                                <span style="background:#f59e0b; color:#fff; font-size:9.5px; font-weight:700; padding:1px 5px; border-radius:999px; margin-left:6px; flex-shrink:0; line-height:1.2;">{{ $adminSktmPending }}</span>
                            @endif
                        </span>
                    </a>
                </div>
            </div>
        @endif

        {{-- ── KELUAR (LOGOUT) ── --}}
        <div class="nav-section open" id="section-logout">
            <div class="nav-items">
                <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm" style="display:none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('sidebarLogoutForm').submit();"
                   class="nav-item"
                   data-tooltip="Keluar"
                   style="color: #ef4444;">
                    <svg class="nav-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    <span class="nav-item-label">Keluar</span>
                </a>
            </div>
        </div>

    </nav>

    {{-- ── User bottom card ── --}}
    <div class="sidebar-user">
        <div class="sidebar-user-card">
            <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Str::limit(Auth::user()->name, 20) }}</div>
                <span class="sidebar-user-role {{ $isSuperAdmin ? 'role-superadmin' : 'role-asatid' }}">
                    {{ $isSuperAdmin ? 'Super Admin' : ($user->lembaga?->jenis ?? 'Admin') }}
                </span>
            </div>
        </div>
    </div>

</aside>

<script>
    // ── Toggle section collapse ──
    function toggleSection(id) {
        const section = document.getElementById(id);
        if (!section) return;
        section.classList.toggle('open');
    }

    // ── Sidebar collapse/expand (desktop) ──
    function toggleSidebar() {
        const sidebar = document.getElementById('appSidebar');
        const main    = document.getElementById('mainContent');
        if (!sidebar) return;
        sidebar.classList.toggle('collapsed');
        if (main) main.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    // ── Mobile open/close ──
    function openSidebarMobile() {
        const sidebar  = document.getElementById('appSidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        if (!sidebar || !overlay) return;
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebarMobile() {
        const sidebar  = document.getElementById('appSidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ── Restore state on page load ──
    document.addEventListener('DOMContentLoaded', function () {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth > 768) {
            const sidebar = document.getElementById('appSidebar');
            const main    = document.getElementById('mainContent');
            if (sidebar) sidebar.classList.add('collapsed');
            if (main) main.classList.add('sidebar-collapsed');
        }
    });
</script>
