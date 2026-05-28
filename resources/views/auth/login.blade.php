<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Portal login DIGIDAS — Sistem Pendataan Terpadu Yayasan Pendidikan Islam PP Darus Sholah">
        <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

        <title>Login — DIGIDAS | YPI Darus Sholah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <style>
            /* ================================
               RESET & BASE
            ================================ */
            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            html, body {
                height: 100%;
            }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: #f0f4f8;
                color: #1e293b;
                min-height: 100vh;
                display: flex;
                align-items: stretch;
            }

            /* ================================
               SPLIT SCREEN LAYOUT
            ================================ */
            .login-wrapper {
                display: flex;
                width: 100%;
                min-height: 100vh;
            }

            /* ---- LEFT PANEL ---- */
            .panel-left {
                display: none; /* hidden on mobile */
                position: relative;
                flex: 0 0 50%;
                background: linear-gradient(160deg, #0f2744 0%, #0a3d2e 50%, #064e3b 100%);
                overflow: hidden;
            }

            /* Subtle grid pattern overlay */
            .panel-left::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
                background-size: 48px 48px;
                z-index: 0;
            }

            /* Glowing orbs */
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                pointer-events: none;
                z-index: 0;
                animation: orb-float 8s ease-in-out infinite;
            }
            .orb-1 {
                width: 480px; height: 480px;
                background: radial-gradient(circle, rgba(16,185,129,0.35) 0%, transparent 70%);
                top: -100px; left: -100px;
                animation-delay: 0s;
            }
            .orb-2 {
                width: 320px; height: 320px;
                background: radial-gradient(circle, rgba(14,116,144,0.3) 0%, transparent 70%);
                bottom: 20px; right: -60px;
                animation-delay: -3s;
            }
            .orb-3 {
                width: 200px; height: 200px;
                background: radial-gradient(circle, rgba(52,211,153,0.2) 0%, transparent 70%);
                top: 55%; left: 55%;
                animation-delay: -6s;
            }

            @keyframes orb-float {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(8px, -12px) scale(1.03); }
                66% { transform: translate(-6px, 8px) scale(0.97); }
            }

            /* Content inside left panel */
            .panel-left-content {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                padding: 56px 48px;
                text-align: center;
                color: #fff;
            }

            .left-logo-wrap {
                position: relative;
                display: inline-block;
                margin-bottom: 24px;
            }

            .left-logo-halo {
                position: absolute;
                inset: -12px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(16,185,129,0.4) 0%, transparent 70%);
                animation: halo-pulse 3s ease-in-out infinite;
            }

            @keyframes halo-pulse {
                0%, 100% { opacity: 0.7; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.12); }
            }

            .left-logo-img {
                width: 340px;
                height: 340px;
                object-fit: contain;
                position: relative;
                z-index: 2;
                filter: drop-shadow(0 8px 32px rgba(16,185,129,0.35));
                animation: logo-in 0.8s ease-out both;
            }

            @keyframes logo-in {
                from { opacity: 0; transform: scale(0.85) translateY(20px); }
                to   { opacity: 1; transform: scale(1) translateY(0); }
            }

            .left-title {
                font-size: 2.75rem;
                font-weight: 900;
                letter-spacing: -1.5px;
                line-height: 1.1;
                margin-bottom: 8px;
                animation: slide-up 0.7s ease-out 0.2s both;
            }

            .left-title span {
                background: linear-gradient(135deg, #34d399 0%, #a7f3d0 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .left-tagline {
                font-size: 1.0625rem;
                color: rgba(255,255,255,0.65);
                font-weight: 500;
                margin-bottom: 40px;
                animation: slide-up 0.7s ease-out 0.35s both;
            }

            /* Feature pills */
            .feature-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
                width: 100%;
                max-width: 340px;
                animation: slide-up 0.7s ease-out 0.5s both;
            }

            .feature-item {
                display: flex;
                align-items: center;
                gap: 14px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 12px;
                padding: 12px 16px;
                text-align: left;
                backdrop-filter: blur(4px);
                transition: background 0.2s, border-color 0.2s;
            }

            .feature-item:hover {
                background: rgba(255,255,255,0.11);
                border-color: rgba(52,211,153,0.35);
            }

            .feature-icon-box {
                width: 36px; height: 36px;
                border-radius: 9px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
                box-shadow: 0 3px 10px rgba(16,185,129,0.35);
            }

            .feature-icon-box svg {
                width: 18px; height: 18px; color: #fff;
            }

            .feature-text strong {
                display: block;
                font-size: 0.875rem;
                font-weight: 700;
                color: #fff;
                line-height: 1.3;
            }

            .feature-text span {
                font-size: 0.75rem;
                color: rgba(255,255,255,0.5);
            }

            /* Bottom badge in left panel */
            .left-badge {
                margin-top: 40px;
                font-size: 0.75rem;
                color: rgba(255,255,255,0.35);
                letter-spacing: 0.08em;
                text-transform: uppercase;
                font-weight: 600;
                animation: slide-up 0.7s ease-out 0.65s both;
            }

            @keyframes slide-up {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ---- RIGHT PANEL ---- */
            .panel-right {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: #f8fafc;
                padding: 32px 24px;
                min-height: 100vh;
            }

            /* Small logo shown only on mobile */
            .mobile-brand {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 28px;
            }

            .mobile-brand-img {
                width: 64px;
                height: 64px;
                object-fit: contain;
                margin-bottom: 10px;
                filter: drop-shadow(0 4px 10px rgba(16,185,129,0.25));
            }

            .mobile-brand-name {
                font-size: 1.625rem;
                font-weight: 900;
                color: #0f2744;
                letter-spacing: -1px;
            }

            .mobile-brand-name span {
                color: #059669;
            }

            /* Form card */
            .form-card {
                width: 100%;
                max-width: 420px;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 24px;
                padding: 40px 36px;
                box-shadow:
                    0 1px 3px rgba(0,0,0,0.04),
                    0 10px 40px rgba(0,0,0,0.07),
                    0 40px 80px -20px rgba(15,39,68,0.06);
                animation: card-in 0.55s cubic-bezier(0.22,1,0.36,1) both;
            }

            @keyframes card-in {
                from { opacity: 0; transform: translateY(28px) scale(0.97); }
                to   { opacity: 1; transform: translateY(0) scale(1); }
            }

            /* Card header */
            .card-header {
                margin-bottom: 28px;
            }

            /* Small accent line above title */
            .header-accent {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 10px;
            }

            .accent-dot {
                width: 8px; height: 8px;
                border-radius: 50%;
                background: #10b981;
                box-shadow: 0 0 6px rgba(16,185,129,0.6);
            }

            .accent-label {
                font-size: 0.6875rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: #10b981;
            }

            .card-title {
                font-size: 1.625rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -0.75px;
                line-height: 1.2;
                margin-bottom: 4px;
            }

            .card-subtitle {
                font-size: 0.875rem;
                color: #64748b;
                font-weight: 400;
            }

            /* Thin divider */
            .card-divider {
                height: 1.5px;
                background: linear-gradient(90deg, #e2e8f0 0%, transparent 80%);
                margin: 20px 0 24px;
                border-radius: 2px;
            }

            /* Alert / error */
            .alert-error {
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #b91c1c;
                padding: 12px 14px;
                border-radius: 12px;
                font-size: 0.8125rem;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 20px;
                line-height: 1.45;
            }

            .alert-error svg {
                width: 16px; height: 16px;
                flex-shrink: 0;
                margin-top: 1px;
                color: #ef4444;
            }

            /* Form fields */
            .form-group {
                margin-bottom: 18px;
            }

            .form-label {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 0.8125rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 7px;
            }

            .form-label svg {
                width: 14px; height: 14px;
                color: #9ca3af;
            }

            .input-wrap {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 13px;
                top: 50%;
                transform: translateY(-50%);
                width: 17px; height: 17px;
                color: #9ca3af;
                pointer-events: none;
                transition: color 0.2s;
            }

            .form-input {
                width: 100%;
                padding: 12px 14px 12px 40px;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                background: #f8fafc;
                font-size: 14.5px;
                font-family: inherit;
                color: #1e293b;
                outline: none;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            }

            .form-input::placeholder {
                color: #cbd5e1;
                font-weight: 400;
            }

            .form-input:focus {
                border-color: #10b981;
                background: #fff;
                box-shadow:
                    0 0 0 3px rgba(16,185,129,0.12),
                    0 2px 8px rgba(16,185,129,0.08);
            }

            .form-input:focus ~ .input-icon {
                color: #10b981;
            }

            /* Remember me row */
            .row-remember {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 4px;
                margin-bottom: 24px;
            }

            .row-remember input[type="checkbox"] {
                width: 16px; height: 16px;
                accent-color: #10b981;
                cursor: pointer;
                border-radius: 4px;
            }

            .row-remember label {
                font-size: 0.8125rem;
                color: #64748b;
                cursor: pointer;
                user-select: none;
            }

            /* Submit button */
            .btn-submit {
                width: 100%;
                padding: 13px 24px;
                border: none;
                border-radius: 12px;
                background: linear-gradient(135deg, #0f2744 0%, #0d4a36 60%, #059669 100%);
                color: #fff;
                font-size: 0.9375rem;
                font-weight: 700;
                font-family: inherit;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                position: relative;
                overflow: hidden;
                transition: transform 0.2s, box-shadow 0.2s;
                box-shadow: 0 4px 16px rgba(15,39,68,0.25);
                letter-spacing: 0.01em;
            }

            .btn-submit::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.12) 50%, transparent 100%);
                transform: translateX(-100%);
                transition: transform 0.55s ease;
            }

            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 28px rgba(15,39,68,0.32);
            }

            .btn-submit:hover::after {
                transform: translateX(100%);
            }

            .btn-submit:active {
                transform: translateY(0);
                box-shadow: 0 3px 10px rgba(15,39,68,0.2);
            }

            .btn-submit svg {
                width: 17px; height: 17px;
            }

            /* Footer text */
            .form-footer {
                text-align: center;
                margin-top: 24px;
                font-size: 0.75rem;
                color: #94a3b8;
            }

            .form-footer strong {
                font-weight: 700;
                color: #64748b;
            }

            /* ================================
               RESPONSIVE — Desktop shows left
            ================================ */
            @media (min-width: 1024px) {
                .panel-left {
                    display: flex;
                    flex-direction: column;
                }

                .mobile-brand {
                    display: none;
                }

                .panel-right {
                    padding: 40px 48px;
                }
            }

            @media (min-width: 1280px) {
                .left-logo-img {
                    width: 380px;
                    height: 380px;
                }

                .left-title {
                    font-size: 3rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="login-wrapper">

            <!-- ======== LEFT PANEL ======== -->
            <div class="panel-left">
                <div class="orb orb-1"></div>
                <div class="orb orb-2"></div>
                <div class="orb orb-3"></div>

                <div class="panel-left-content">
                    <!-- Logo -->
                    <div class="left-logo-wrap">
                        <div class="left-logo-halo"></div>
                        <img src="{{ asset('images/ppd.png') }}" alt="Logo PP Darus Sholah" class="left-logo-img">
                    </div>

                    <!-- Title -->
                    <h1 class="left-title">DIGIDAS <span>YPI</span></h1>
                    <p class="left-tagline">Digital Data System — Yayasan Pendidikan Islam<br>PP Darus Sholah</p>

                    <p class="left-badge">Yayasan Pendidikan Islam PP Darus Sholah</p>
                </div>
            </div>

            <!-- ======== RIGHT PANEL ======== -->
            <div class="panel-right">

                <!-- Mobile brand (only visible < 1024px) -->
                <div class="mobile-brand">
                    <img src="{{ asset('images/ppd.png') }}" alt="Logo" class="mobile-brand-img">
                    <div class="mobile-brand-name">DIGI<span>DAS</span></div>
                </div>

                <!-- Form card -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="header-accent">
                            <div class="accent-dot"></div>
                            <span class="accent-label">Portal Yayasan</span>
                        </div>
                        <h2 class="card-title">Selamat Datang!</h2>
                    </div>

                    <div class="card-divider"></div>

                    <!-- Error messages -->
                    @if($errors->any())
                        <div class="alert-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <!-- Username -->
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Username
                            </label>
                            <div class="input-wrap">
                                <input
                                    id="username"
                                    type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="form-input"
                                    placeholder="Masukkan username Anda"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="input-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                Password
                            </label>
                            <div class="input-wrap">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="form-input"
                                    placeholder="Masukkan password"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="input-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Remember me -->
                        <div class="row-remember">
                            <input id="remember_me" type="checkbox" name="remember">
                            <label for="remember_me">Ingat saya di perangkat ini</label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" id="btn-login" class="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <!-- Footer -->
                    <p class="form-footer">
                        &copy; {{ date('Y') }} — <strong>DIGIDAS YPI</strong> &middot; Yayasan Pendidikan Islam PP Darus Sholah
                    </p>
                </div>
            </div>

        </div>
    </body>
</html>
