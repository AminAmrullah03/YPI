<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DIGIDAS') — YPI PP Darus Sholah</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js" defer></script>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.50.0/dist/apexcharts.min.js" defer></script>

    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
        }

        /* ── Shell layout ── */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .main-content.sidebar-collapsed {
            margin-left: 68px;
        }

        /* ── Page content ── */
        .page-content {
            flex: 1;
            padding: 24px 24px 40px;
            min-width: 0;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
            }
            .page-content {
                padding: 16px 16px 32px;
            }
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            animation: slideDownAlert 0.2s ease-out;
        }
        @keyframes slideDownAlert {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-error   { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
    </style>
    @stack('styles')
</head>
<body>

<div class="app-shell">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main --}}
    <div class="main-content" id="mainContent">

        {{-- Topbar --}}
        @include('layouts.topbar')

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="padding: 16px 24px 0;">
                <div class="alert alert-success" data-auto-hide>
                    <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 16px 24px 0;">
                <div class="alert alert-error" data-auto-hide>
                    <i class="ph ph-warning-circle" style="font-size: 18px;"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
