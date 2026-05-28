{{-- ============================================================
     TOPBAR — DIGIDAS YPI (Desktop reference)
     Compact, Breadcrumb, Live Clock, Profile Dropdown
     ============================================================ --}}

@php
    $user = auth()->user();
    $isSuperAdmin = $user->isSuperAdmin();
@endphp

<style>
    .topbar {
        position: sticky;
        top: 0;
        z-index: 900;
        height: 60px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(226,232,240,0.8);
        box-shadow: 0 1px 12px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        padding: 0 20px 0 16px;
        gap: 12px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }

    /* ── Toggle button ── */
    .topbar-toggle {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: none;
        background: transparent;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.18s;
        flex-shrink: 0;
    }
    .topbar-toggle:hover {
        background: #f1f5f9;
        color: #10b981;
    }
    .topbar-toggle svg { width: 20px; height: 20px; }

    /* ── Breadcrumb ── */
    .topbar-breadcrumb {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        color: #94a3b8;
        min-width: 0;
        overflow: hidden;
    }
    .topbar-breadcrumb a {
        color: #94a3b8;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.15s;
        white-space: nowrap;
    }
    .topbar-breadcrumb a:hover { color: #10b981; }
    .topbar-breadcrumb .bc-sep {
        color: #cbd5e1;
        flex-shrink: 0;
        font-size: 0.7rem;
    }
    .topbar-breadcrumb .bc-current {
        color: #1e293b;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Right section ── */
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* ── Notif button ── */
    .topbar-notif {
        position: relative;
        width: 38px; height: 38px;
        border-radius: 10px;
        border: none;
        background: transparent;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.18s;
    }
    .topbar-notif:hover {
        background: #f1f5f9;
        color: #10b981;
    }
    .topbar-notif svg { width: 20px; height: 20px; }
    .notif-badge {
        position: absolute;
        top: 6px; right: 6px;
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #ef4444;
        border: 2px solid #fff;
        animation: pulseBadge 2s ease-in-out infinite;
    }
    @keyframes pulseBadge {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
        50% { box-shadow: 0 0 0 4px rgba(239,68,68,0); }
    }

    /* ── Clock display ── */
    .topbar-clock {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        padding: 0 4px;
    }
    .topbar-clock-time {
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e293b;
        font-variant-numeric: tabular-nums;
        line-height: 1;
    }
    .topbar-clock-date {
        font-size: 0.6rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
        white-space: nowrap;
    }

    /* ── Divider ── */
    .topbar-divider {
        width: 1px; height: 24px;
        background: #e2e8f0;
        flex-shrink: 0;
    }

    /* ── Profile trigger ── */
    .topbar-profile {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 10px 5px 6px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        cursor: pointer;
        transition: all 0.18s;
        font-family: inherit;
    }
    .topbar-profile:hover {
        border-color: rgba(16,185,129,0.4);
        background: rgba(16,185,129,0.03);
        box-shadow: 0 2px 8px rgba(16,185,129,0.1);
    }
    .topbar-avatar {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; font-weight: 800; color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(16,185,129,0.3);
    }
    .topbar-profile-info { text-align: left; }
    .topbar-profile-name {
        font-size: 0.775rem;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }
    .topbar-profile-role {
        font-size: 0.6rem;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: inline-block;
    }
    .tpr-superadmin { background: rgba(167,139,250,0.12); color: #8b5cf6; border: 1px solid rgba(167,139,250,0.25); }
    .tpr-asatid      { background: rgba(16,185,129,0.1);  color: #059669; border: 1px solid rgba(16,185,129,0.2); }
    .topbar-profile-chevron {
        color: #cbd5e1;
        transition: transform 0.2s, color 0.2s;
        flex-shrink: 0;
    }
    .topbar-profile:hover .topbar-profile-chevron { color: #10b981; }

    /* ── Dropdown ── */
    .topbar-dropdown {
        position: absolute;
        right: 0; top: calc(100% + 8px);
        width: 220px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 16px 48px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.06);
        overflow: hidden;
        transform-origin: top right;
        animation: dropIn 0.18s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        z-index: 9999;
    }
    @keyframes dropIn {
        from { opacity: 0; transform: scale(0.92) translateY(-6px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .topbar-dropdown-header {
        padding: 14px 16px 12px;
        background: linear-gradient(135deg, #f8fafc, #f0fdf4);
        border-bottom: 1px solid #f1f5f9;
    }
    .topbar-dropdown-header .dd-name { font-size: 0.875rem; font-weight: 700; color: #1e293b; }
    .topbar-dropdown-header .dd-nip  { font-size: 0.7rem; color: #94a3b8; font-family: monospace; margin-top: 2px; }
    .topbar-dropdown a,
    .topbar-dropdown button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        text-align: left;
        font-family: inherit;
    }
    .topbar-dropdown a:hover { background: #f8fafc; color: #10b981; padding-left: 20px; }
    .topbar-dropdown button:hover { background: #fff5f5; color: #ef4444; padding-left: 20px; }
    .topbar-dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }

    /* ── Mobile toggle (show on mobile) ── */
    .topbar-mobile-menu {
        display: none;
        width: 38px; height: 38px;
        border-radius: 10px;
        border: none;
        background: transparent;
        align-items: center; justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.18s;
    }
    .topbar-mobile-menu:hover { background: #f1f5f9; color: #10b981; }
    .topbar-mobile-menu svg { width: 20px; height: 20px; }

    @media (max-width: 768px) {
        .topbar-mobile-menu { display: flex; }
        .topbar-toggle { display: none; }
        .topbar-clock { display: none; }
        .topbar-breadcrumb .bc-current { max-width: 120px; }
        .topbar-profile-info { display: none; }
    }
</style>

<header class="topbar">

    {{-- Toggle sidebar (desktop) --}}
    <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    {{-- Toggle sidebar (mobile) --}}
    <button class="topbar-mobile-menu" onclick="openSidebarMobile()" title="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    {{-- Breadcrumb --}}
    <nav class="topbar-breadcrumb">
        <a href="{{ $isSuperAdmin ? route('super-admin.dashboard') : route('admin.dashboard') }}">
            <svg style="width:13px;height:13px;margin-bottom:-1px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2.25 12l9.204-8.284a.75.75 0 011.004 0L21.75 12M4.5 10.5V19.5h15V10.5"/>
            </svg>
        </a>
        @isset($breadcrumb)
            @foreach($breadcrumb as $item)
                <span class="bc-sep">›</span>
                @if(!$loop->last)
                    <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
                @else
                    <span class="bc-current">{{ $item['label'] }}</span>
                @endif
            @endforeach
        @else
            <span class="bc-sep">›</span>
            <span class="bc-current">
                @if(request()->routeIs('super-admin.dashboard') || request()->routeIs('admin.dashboard')) Dashboard
                @elseif(request()->routeIs('*.siswa.*')) Manajemen Siswa
                @elseif(request()->routeIs('*.guru.*')) Manajemen Guru
                @elseif(request()->routeIs('*.lembaga.*')) Manajemen Lembaga
                @elseif(request()->routeIs('*.users.*')) Kelola User
                @elseif(request()->routeIs('*.laporan.*')) Laporan & Export
                @elseif(request()->routeIs('*.audit-log.*')) Audit Log
                @elseif(request()->routeIs('password.change')) Ganti Password
                @else @yield('page-title', 'Halaman')
                @endif
            </span>
        @endisset
    </nav>

    {{-- Right section --}}
    <div class="topbar-right">

        {{-- Lembaga Badge for Admin Lembaga --}}
        @if(auth()->user()->isAdminLembaga() && auth()->user()->lembaga)
            <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:20px; padding:4px 12px; display:flex; align-items:center; gap:6px; flex-shrink:0;">
                <svg style="width:13px;height:13px;color:#10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18M3 10h18M5 6h14a2 2 0 0 1 2 2v13H3V8a2 2 0 0 1 2-2zM9 14h6m-6 4h6"/>
                </svg>
                <span style="font-size:12px; font-weight:600; color:#10b981; white-space:nowrap;">{{ auth()->user()->lembaga->jenis_label }}</span>
            </div>
        @endif

        {{-- Clock --}}
        <div class="topbar-clock">
            <div class="topbar-clock-time" id="topbarClock">--:--</div>
            <div class="topbar-clock-date" id="topbarDate">--</div>
        </div>

        <div class="topbar-divider"></div>

        {{-- Notification bell --}}
        <button class="topbar-notif" title="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="notif-badge"></span>
        </button>

        <div class="topbar-divider"></div>

        {{-- Profile dropdown --}}
        <div style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" class="topbar-profile">
                <div class="topbar-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div class="topbar-profile-info">
                    <div class="topbar-profile-name">{{ Str::limit($user->name, 18) }}</div>
                    <span class="topbar-profile-role {{ $isSuperAdmin ? 'tpr-superadmin' : 'tpr-asatid' }}">
                        {{ $isSuperAdmin ? 'Superadmin' : ($user->lembaga?->jenis ?? 'Admin') }}
                    </span>
                </div>
                <svg class="topbar-profile-chevron" :style="open ? 'transform:rotate(180deg)' : ''" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>

            <div x-show="open" class="topbar-dropdown" style="display:none;">
                <div class="topbar-dropdown-header">
                    <div class="dd-name">{{ $user->name }}</div>
                    <div class="dd-nip">Username: {{ $user->username }}</div>
                </div>
                <div style="padding:4px 0;">
                    <a href="{{ route('password.change') }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Ganti Password
                    </a>
                </div>
                <div class="topbar-dropdown-divider"></div>
                <div style="padding:4px 0;">
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// ── Live clock ──
function updateClock() {
    const now  = new Date();
    const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const date = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
    const el   = document.getElementById('topbarClock');
    const elD  = document.getElementById('topbarDate');
    if (el) el.textContent  = time;
    if (elD) elD.textContent = date;
}
updateClock();
setInterval(updateClock, 1000);
</script>
