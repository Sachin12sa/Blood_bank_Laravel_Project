<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodBank — @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --red: #e02020;
            --red-dark: #b91414;
            --red-soft: #fff0f0;
            --red-mid: #ffd6d6;
            --sidebar-w: 260px;
            --nav-h: 64px;
            --footer-h: 56px;
            --gray-50: #f8f9fb;
            --gray-100: #f1f3f7;
            --gray-200: #e4e7ef;
            --gray-500: #7b829a;
            --gray-700: #3a3f52;
            --gray-900: #171b2d;
            --font: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 1px 4px rgba(0, 0, 0, .07);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, .09);
            --radius: 12px;
            --radius-sm: 8px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font);
            background: var(--gray-50);
            color: var(--gray-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ─────────────────────────────── */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--nav-h);
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            box-shadow: var(--shadow-sm);
        }

        .top-navbar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--gray-900);
            letter-spacing: -.3px;
            min-width: calc(var(--sidebar-w) - 24px);
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--red);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(224, 32, 32, .35);
        }

        .brand span.sub {
            color: var(--red);
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--gray-700);
            cursor: pointer;
            padding: 6px;
        }

        .navbar-search {
            flex: 1;
            max-width: 380px;
            position: relative;
        }

        .navbar-search input {
            width: 100%;
            background: var(--gray-100);
            border: 1.5px solid transparent;
            border-radius: 10px;
            padding: 8px 12px 8px 38px;
            font-family: var(--font);
            font-size: .875rem;
            color: var(--gray-900);
            transition: border-color .2s, background .2s;
            outline: none;
        }

        .navbar-search input:focus {
            background: #fff;
            border-color: var(--red);
        }

        .navbar-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            font-size: .9rem;
            pointer-events: none;
        }

        .navbar-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gray-100);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            transition: background .2s;
            text-decoration: none;
        }

        .nav-icon-btn:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .nav-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .nav-divider {
            width: 1px;
            height: 28px;
            background: var(--gray-200);
            margin: 0 4px;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px 6px 6px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
            color: var(--gray-900);
        }

        .nav-user:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .nav-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .85rem;
            color: #fff;
            flex-shrink: 0;
        }

        .nav-avatar.admin {
            background: linear-gradient(135deg, #e02020, #b91414);
        }

        .nav-avatar.donor {
            background: linear-gradient(135deg, #f97316, #ea580c);
        }

        .nav-avatar.hospital {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .nav-user-info {
            line-height: 1.2;
        }

        .nav-user-name {
            font-size: .875rem;
            font-weight: 600;
        }

        .nav-user-role {
            font-size: .72rem;
            color: var(--gray-500);
            text-transform: capitalize;
        }

        /* ── SIDEBAR ─────────────────────────────── */
        .sidebar {
            position: fixed;
            top: var(--nav-h);
            left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--nav-h));
            background: #fff;
            border-right: 1px solid var(--gray-200);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: transform .3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-200);
            border-radius: 4px;
        }

        .sidebar-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gray-500);
            padding: 10px 12px 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--gray-700);
            font-size: .875rem;
            font-weight: 500;
            transition: background .18s, color .18s;
            position: relative;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .sidebar-link.active {
            background: var(--red-soft);
            color: var(--red);
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: var(--red);
        }

        .sidebar-link .s-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-link .s-badge.warn {
            background: #f59e0b;
        }

        .sidebar-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 8px 0;
        }

        /* ── MAIN CONTENT ────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            margin-top: var(--nav-h);
            min-height: calc(100vh - var(--nav-h) - var(--footer-h));
            padding: 28px 28px 0;
            flex: 1;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h4 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -.3px;
        }

        .page-header p {
            color: var(--gray-500);
            font-size: .875rem;
            margin-top: 2px;
        }

        .breadcrumb {
            font-size: .8rem;
        }

        .breadcrumb-item a {
            color: var(--red);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--gray-500);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--gray-500);
        }

        /* ── CARDS ───────────────────────────────── */
        .card {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .stat-card {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 20px;
            background: #fff;
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .stat-icon.red {
            background: var(--red-soft);
            color: var(--red);
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        .stat-icon.orange {
            background: #fff7ed;
            color: #f97316;
        }

        .stat-icon.green {
            background: #f0fdf4;
            color: #22c55e;
        }

        .stat-icon.purple {
            background: #faf5ff;
            color: #a855f7;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -.5px;
            line-height: 1;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--gray-500);
            margin-top: 4px;
            font-weight: 500;
        }

        /* ── ALERTS ──────────────────────────────── */
        .alert {
            border: none;
            border-radius: var(--radius-sm);
            font-size: .875rem;
            border-left: 4px solid transparent;
        }

        .alert-success {
            background: #f0fdf4;
            border-left-color: #22c55e;
            color: #15803d;
        }

        .alert-danger {
            background: #fef2f2;
            border-left-color: var(--red);
            color: var(--red-dark);
        }

        .alert-warning {
            background: #fffbeb;
            border-left-color: #f59e0b;
            color: #b45309;
        }

        .alert-info {
            background: #eff6ff;
            border-left-color: #3b82f6;
            color: #1d4ed8;
        }

        /* ── TABLES ──────────────────────────────── */
        .table {
            font-size: .875rem;
        }

        .table thead th {
            background: var(--gray-50);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--gray-500);
            border-bottom: 1px solid var(--gray-200);
            padding: 10px 14px;
        }

        .table td {
            padding: 12px 14px;
            vertical-align: middle;
            border-color: var(--gray-200);
        }

        /* ── BADGES ──────────────────────────────── */
        .badge-blood {
            background: var(--red);
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .75rem;
            font-weight: 700;
        }

        /* ── BUTTONS ─────────────────────────────── */
        .btn-primary {
            background: var(--red);
            border-color: var(--red);
        }

        .btn-primary:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        /* ── FOOTER ──────────────────────────────── */
        .main-footer {
            margin-left: var(--sidebar-w);
            height: var(--footer-h);
            background: #fff;
            border-top: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            font-size: .78rem;
            color: var(--gray-500);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .footer-brand .dot {
            width: 8px;
            height: 8px;
            background: var(--red);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .6;
                transform: scale(1.3);
            }
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            color: var(--gray-500);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-links a:hover {
            color: var(--red);
        }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-wrapper,
            .main-footer {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }

            .navbar-search {
                max-width: 200px;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .4);
                z-index: 1025;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .navbar-search {
                display: none;
            }

            .nav-user-info {
                display: none;
            }

            .main-wrapper {
                padding: 16px 16px 0;
            }

            .main-footer {
                padding: 0 16px;
            }

            .footer-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- ── SIDEBAR OVERLAY (mobile) ──────────────── --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ══════════════════════════════════════════════
     TOP NAVBAR
══════════════════════════════════════════════ --}}
    <nav class="top-navbar">

        {{-- Mobile toggle --}}
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <div class="brand-icon">
                <i class="bi bi-droplet-fill"></i>
            </div>
            Blood<span class="sub">Bank</span>
        </a>

        {{-- Search --}}
        <div class="navbar-search d-none d-md-block">
            <i class="bi bi-search search-icon"></i>
            <input type="text" placeholder="Search donors, requests…">
        </div>

        {{-- Actions --}}
        <div class="navbar-actions">

            {{-- Notifications --}}
            <a href="#" class="nav-icon-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="nav-badge"></span>
            </a>

            {{-- Help --}}
            <a href="#" class="nav-icon-btn d-none d-sm-flex" title="Help">
                <i class="bi bi-question-circle"></i>
            </a>

            <div class="nav-divider"></div>

            {{-- User dropdown --}}
            {{-- <div class="dropdown">
                <a class="nav-user dropdown-toggle" data-bs-toggle="dropdown" href="#"
                    style="text-decoration:none; color:inherit">
                    @php
                        $role = auth()->user()->getRoleNames()->first() ?? 'user';
                        $init = strtoupper(substr(auth()->user()->name, 0, 2));
                    @endphp
                    <div class="nav-avatar {{ $role }}">{{ $init }}</div>
                    <div class="nav-user-info d-none d-sm-block">
                        <div class="nav-user-name">{{ auth()->user()->name }}</div>
                        <div class="nav-user-role">{{ $role }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                    style="border-radius:12px; min-width:200px; font-size:.875rem; padding:8px">
                    <li>
                        <div class="px-3 py-2 mb-1 border-bottom">
                            <div style="font-weight:600">{{ auth()->user()->name }}</div>
                            <div style="font-size:.75rem;color:var(--gray-500)">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2" href="#">
                            <i class="bi bi-person me-2"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2" href="#">
                            <i class="bi bi-gear me-2"></i> Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item rounded-2 text-danger" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div> --}}

        </div>
    </nav>

    {{-- ══════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar">
        @yield('sidebar')
    </aside>

    {{-- ══════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════ --}}
    <main class="main-wrapper">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- ══════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════ --}}
    <footer class="main-footer">
        <div class="footer-brand">
            <div class="dot"></div>
            BloodBank System
            <span style="font-weight:400;color:var(--gray-500)">v1.0</span>
        </div>

        <div style="color:var(--gray-500)">
            © {{ date('Y') }} BloodBank. All rights reserved.
        </div>

        <div class="footer-links">
            <a href="#">Documentation</a>
            <a href="#">Support</a>
            <a href="#">Privacy</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            const o = document.getElementById('sidebarOverlay');
            s.classList.toggle('open');
            o.classList.toggle('active');
        }
    </script>
    @stack('scripts')
</body>

</html>
