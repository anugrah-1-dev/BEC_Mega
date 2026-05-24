<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal BEC — Brilliant English Course</title>
    <meta name="description" content="Portal Pendaftaran Resmi Brilliant English Course Kampung Inggris Kediri">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #363d72;
            --primary-dark:  #2f3565;
            --primary-glow:  rgba(54, 61, 114, 0.25);
            --accent:        #f59e0b;
            --accent-light:  #fef3c7;
            --success:       #10b981;
            --success-light: #d1fae5;
            --warning:       #f59e0b;
            --warning-light: #fffbeb;
            --danger:        #ef4444;
            --danger-light:  #fee2e2;
            --bg:            #f8fafc;
            --sidebar-bg:    #ffffff;
            --sidebar-w:     260px;
            --text:          #1e293b;
            --muted:         #64748b;
            --border:        #e2e8f0;
            --card-bg:       #ffffff;
            --radius-lg:     20px;
            --radius-md:     14px;
            --radius-sm:     8px;
            --shadow-md:     0 4px 24px rgba(0,0,0,0.08);
            --shadow-lg:     0 12px 40px rgba(0,0,0,0.12);
        }

        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0;
            z-index: 200;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .logo-img {
            height: 60px;
            width: auto;
            display: block;
        }
        .sidebar-brand .brand-name {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 5px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 15px;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
            padding: 6px 12px 10px;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 5px;
            transition: all 0.2s;
        }
        .nav-item:hover { background: #f1f5f9; color: var(--text); }
        .nav-item.active {
            background: var(--primary);
            color: white;
            box-shadow: none;
        }
        .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 15px;
            border-top: 1px solid var(--border);
        }
        .nav-item.logout { color: #ef4444; background: #fef2f2; justify-content: center; font-weight: 700; }
        .nav-item.logout:hover { background: #fee2e2; color: #dc2626; }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--primary);
            border-bottom: none;
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: #ffffff;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-weight: 800; font-size: 0.95rem;
        }
        .user-info { text-align: right; }
        .user-info .name { font-size: 0.9rem; font-weight: 700; color: white; }
        .user-info .role { font-size: 0.72rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }

        /* ===== PAGE BODY ===== */
        .page-body {
            flex: 1;
            padding: 32px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: var(--success-light); color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger  { background: var(--danger-light);  color: #991b1b; border: 1px solid #fca5a5; }

        /* ===== FORMS ===== */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: #fafafa;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .form-error { font-size: 0.78rem; color: var(--danger); margin-top: 6px; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: var(--radius-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px var(--primary-glow);
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-success { background: var(--success); color: white; box-shadow: 0 4px 14px rgba(16,185,129,0.3); }
        .btn-success:hover { background: #059669; transform: translateY(-1px); }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--muted);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 8px 16px; font-size: 0.8rem; }
        .btn-lg { padding: 15px 32px; font-size: 1rem; }
        .btn-block { width: 100%; }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-pending  { background: var(--warning-light); color: #92400e; }
        .badge-success  { background: var(--success-light); color: #065f46; }
        .badge-danger   { background: var(--danger-light);  color: #991b1b; }
        .badge-primary  { background: rgba(54, 61, 114, 0.1);  color: var(--primary); }

        /* ===== STEP INDICATOR ===== */
        .steps-bar {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 40px;
        }
        .step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        .step-node::before {
            content: '';
            position: absolute;
            top: 20px; right: 50%; left: -50%;
            height: 2px;
            background: var(--border);
        }
        .step-node:first-child::before { display: none; }
        .step-node.done::before   { background: var(--primary); }
        .step-node.active::before { background: var(--primary); }

        .step-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 800;
            color: var(--muted);
            margin-bottom: 8px;
            transition: all 0.3s;
        }
        .step-node.active .step-circle {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
            box-shadow: 0 0 0 5px var(--primary-glow);
        }
        .step-node.done .step-circle {
            border-color: var(--success);
            background: var(--success);
            color: white;
        }
        .step-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--muted);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .step-node.active .step-label { color: var(--primary); }
        .step-node.done  .step-label  { color: var(--success); }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table th {
            padding: 12px 20px;
            background: #f8fafc;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
            border-bottom: 1.5px solid var(--border);
            text-align: left;
        }
        table.data-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: var(--text);
            vertical-align: middle;
        }
        table.data-table tr:hover td { background: #fafbff; }

        /* ===== STAT CARD ===== */
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 24px 28px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 26px; height: 26px; }
        .stat-value { font-size: 2rem; font-weight: 900; line-height: 1; }
        .stat-label { font-size: 0.775rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 4px; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @yield('extra-styles')
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="app-sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC Logo" class="logo-img">
        </div>

        <nav class="sidebar-nav">
            @auth
                @if(Auth::user()->role === 'admin')
                    <div class="nav-section-title">Admin Panel</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pendaftar') }}" class="nav-item {{ request()->routeIs('admin.pendaftar*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M16,11C17.66,11 18.99,9.66 18.99,8C18.99,6.34 17.66,5 16,5C14.34,5 13,6.34 13,8C13,9.66 14.34,11 16,11M8,11C9.66,11 10.99,9.66 10.99,8C10.99,6.34 9.66,5 8,5C6.34,5 5,6.34 5,8C5,9.66 6.34,11 8,11M8,13C5.67,13 1,14.17 1,16.5V18H15V16.5C15,14.17 10.33,13 8,13M16,13C15.71,13 15.38,13.02 15.03,13.05C16.19,13.89 17,15.02 17,16.5V18H23V16.5C23,14.17 18.33,13 16,13Z"/></svg>
                        Data Pendaftar
                    </a>
                    <a href="{{ route('admin.kelola_data') }}" class="nav-item {{ request()->routeIs('admin.kelola_data') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.97 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.97 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/></svg>
                        Kelola Data Master
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V3.5L18.5,9M6,2C4.89,2 4,2.89 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6Z"/></svg>
                        Laporan Keuangan
                    </a>
                    <a href="{{ route('admin.siswa') }}" class="nav-item {{ request()->routeIs('admin.siswa') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                        Manajemen Siswa
                    </a>
                @else
                    <div class="nav-section-title">Menu Saya</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('isi_data') }}" class="nav-item {{ request()->routeIs('isi_data') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                        Data Diri
                    </a>
                    <a href="{{ route('pilih_course') }}" class="nav-item {{ request()->routeIs('pilih_course') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19,2L14,6.5V17.5L19,13V2M6.5,5C4.55,5 2.45,5.4 1,6.5V21.16C1,21.41 1.25,21.66 1.5,21.66C1.6,21.66 1.65,21.59 1.75,21.59C3.1,20.94 5.05,20.5 6.5,20.5C8.45,20.5 10.55,20.9 12,22C13.35,21.15 15.8,20.5 17.5,20.5C19.15,20.5 20.85,20.81 22.25,21.56C22.35,21.61 22.4,21.59 22.5,21.59C22.75,21.59 23,21.34 23,21.09V6.5C22.4,6.05 21.75,5.75 21,5.5V19C19.9,18.65 18.7,18.5 17.5,18.5C15.8,18.5 13.35,19.15 12,20V6.5C10.55,5.4 8.45,5 6.5,5Z"/></svg>
                        Pilih Kursus
                    </a>
                    <a href="{{ route('upload_bayar') }}" class="nav-item {{ request()->routeIs('upload_bayar') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/></svg>
                        Pembayaran
                    </a>
                    <a href="{{ route('lihat_status') }}" class="nav-item {{ request()->routeIs('lihat_status') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M11,17H13V11H11V17M11,9H13V7H11V9Z"/></svg>
                        Status Pendaftaran
                    </a>
                @endif

                <div style="height: 16px;"></div>
                <a href="{{ route('home') }}" class="nav-item">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"/></svg>
                    Kembali ke Tour
                </a>
            @endauth
        </nav>

        <div class="sidebar-footer">
            @auth
            <a href="{{ route('logout') }}" class="nav-item logout"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M17,17.25V14H10V10H17V6.75L22.25,12L17,17.25M13,2A2,2 0 0,1 15,4V8H13V4H4V20H13V16H15V20A2,2 0 0,1 13,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H13Z"/></svg>
                Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            @endauth
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title">
                @yield('page-title', 'Portal BEC')
            </div>
            @auth
            <div class="topbar-user">
                <div class="user-info">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pendaftar' }}</div>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
            @endauth
        </header>

        <main class="page-body">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('components.audio-player')
    @yield('extra-scripts')
</body>
</html>
