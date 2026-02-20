<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Presensi BPS Jambi' }}</title>
    <meta name="description" content="Sistem Presensi Digital BPS Provinsi Jambi">
    <link rel="icon" type="image/png" href="{{ asset('logobps.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #ede9fe;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --bg-main: #f1f5f9;
            --bg-card: #ffffff;
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: rgba(255,255,255,0.08);
            --sidebar-active: rgba(255,255,255,0.15);
            --sidebar-text: rgba(255,255,255,0.75);
            --sidebar-text-active: #ffffff;
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --bottom-nav-height: 64px;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            line-height: 1.5;
        }

        /* ============================================
           SIDEBAR
        ============================================ */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }

        .sidebar-logo img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo-text .app-name {
            font-weight: 700;
            font-size: 1rem;
            color: white;
            line-height: 1.1;
        }

        .sidebar-logo-text .app-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.05em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
        }

        .nav-group-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 0.75rem 0.5rem 0.25rem;
            margin-top: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.875rem;
            border-radius: var(--radius-sm);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
            font-weight: 600;
        }

        .nav-item.active svg {
            color: #a5b4fc;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            color: rgba(255,255,255,0.5);
            transition: color 0.2s;
        }

        .nav-item:hover svg {
            color: rgba(255,255,255,0.85);
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        /* ============================================
           TOPBAR
        ============================================ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 100;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            transition: background 0.2s;
        }

        .hamburger-btn:hover {
            background: var(--bg-main);
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content {
            padding: calc(var(--topbar-height) + 1.75rem) 1.75rem 2rem;
        }

        /* ============================================
           ACCOUNT DROPDOWN
        ============================================ */
        .account-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 0.3rem 0.75rem 0.3rem 0.3rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .account-btn:hover {
            background: var(--bg-main);
            border-color: var(--text-muted);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-info {
            text-align: left;
            line-height: 1.2;
        }

        .account-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-main);
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .account-role {
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        /* Dropdown */
        .dropdown-wrap {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            min-width: 220px;
            overflow: hidden;
            animation: dropIn 0.18s ease-out;
            z-index: 500;
        }

        .dropdown-menu.show { display: block; }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 0.875rem 1rem;
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            border-bottom: 1px solid var(--border);
        }

        .dropdown-header .dh-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: #3730a3;
        }

        .dropdown-header .dh-nip {
            font-size: 0.75rem;
            color: #5b21b6;
            margin-top: 0.1rem;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            text-decoration: none;
            color: var(--text-main);
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s;
            border: none;
            width: 100%;
            text-align: left;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }

        .dropdown-item:hover { background: var(--bg-main); }

        .dropdown-item svg {
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 0.25rem 0;
        }

        .dropdown-section-label {
            padding: 0.5rem 1rem 0.25rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .dropdown-item.danger { color: var(--danger); }
        .dropdown-item.danger svg { color: var(--danger); }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* ============================================
           ALERTS / FLASH
        ============================================ */
        .alert {
            padding: 0.875rem 1.125rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid;
        }

        .alert-success { background: #f0fdf4; border-color: #86efac; color: #166534; }
        .alert-error { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }

        /* ============================================
           BUTTONS
        ============================================ */
        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: var(--bg-main);
            border-color: var(--text-muted);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-sm {
            padding: 0.4rem 0.875rem;
            font-size: 0.8rem;
        }

        /* ============================================
           CARDS
        ============================================ */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .card-body { padding: 1.5rem; }
        .card-header {
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ============================================
           FORMS
        ============================================ */
        .form-group { margin-bottom: 1.25rem; }

        label {
            display: block;
            margin-bottom: 0.375rem;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        input, select, textarea {
            width: 100%;
            padding: 0.7rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--bg-card);
            color: var(--text-main);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* ============================================
           UTILITY
        ============================================ */
        .animate-fade { animation: fadeUp 0.4s ease forwards; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        .page-header p {
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }

        /* Tables */
        .table-container { overflow-x: auto; border-radius: var(--radius-md); }

        table { width: 100%; border-collapse: collapse; }

        thead th {
            background: var(--bg-main);
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.625rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-primary { background: var(--primary-light); color: #4338ca; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        /* ============================================
           BOTTOM NAVIGATION (Mobile)
        ============================================ */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background: white;
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.07);
            z-index: 300;
            padding: 0 0.25rem;
            align-items: center;
            justify-content: space-around;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.62rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
            flex: 1;
        }

        .bottom-nav-item svg {
            width: 22px;
            height: 22px;
            transition: all 0.2s;
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item.active svg {
            color: var(--primary);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 190;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show { display: block; }

        /* ============================================
           GLASS CARD
        ============================================ */
        .glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.5);
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }

            .topbar {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .account-info { display: none; }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: calc(var(--topbar-height) + 1.25rem) 1rem calc(var(--bottom-nav-height) + 1.5rem);
            }

            .bottom-nav {
                display: flex;
            }

            .topbar-right .account-btn {
                padding: 0.3rem;
                border: none;
                background: transparent;
            }
        }

        @media (max-width: 480px) {
            .topbar { padding: 0 1rem; }
            .main-content { padding-left: 0.875rem; padding-right: 0.875rem; }
        }

        /* Grid helpers */
        .grid { display: grid; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-4 { gap: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .text-center { text-align: center; }
        .font-semibold { font-weight: 600; }
        .text-muted { color: var(--text-muted); }
        .text-sm { font-size: 0.875rem; }
    </style>
    @stack('styles')
</head>
<body>
@auth
@php
    $user = Auth::user();
    $userRoles = session('roles', []);
    $roleNames = session('role_names', []);
    $currentRole = strtolower(session('role_name', ''));
    $currentRoleDisp = session('role_name', 'User');
    $isAdmin = in_array($currentRole, ['admin', 'operator']) || $user->username == 'admin';

    // Determine active nav
    $route = request()->route()->getName();
@endphp

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ========= SIDEBAR ========= -->
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <img src="{{ asset('logobps.png') }}" alt="BPS Logo">
        <div class="sidebar-logo-text">
            <span class="app-name">Presensi BPS</span>
            <span class="app-sub">Provinsi Jambi</span>
        </div>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-group-label">Umum</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ $route === 'dashboard' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <a href="{{ route('profile.index') }}" class="nav-item {{ $route === 'profile.index' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil Saya
        </a>

        @if(!$isAdmin)
        <a href="{{ route('employee.scan-qr') }}" class="nav-item {{ $route === 'employee.scan-qr' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Scan QR Saya
        </a>
        @endif

        @if($isAdmin)
        <div class="nav-group-label">Administrasi</div>
        <a href="{{ route('admin.sessions.index') }}" class="nav-item {{ str_starts_with($route ?? '', 'admin.sessions') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Kegiatan
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ str_starts_with($route ?? '', 'admin.users') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Anggota
        </a>
        <a href="{{ route('admin.scan-qr') }}" class="nav-item {{ $route === 'admin.scan-qr' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Scan QR
        </a>
        <a href="{{ route('admin.show-qr') }}" class="nav-item {{ $route === 'admin.show-qr' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 1118 0A9 9 0 016 12z"/></svg>
            Show QR
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item {{ $route === 'admin.reports' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Laporan
        </a>
        @endif
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-radius: var(--radius-sm); background: rgba(255,255,255,0.06);">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #a855f7); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; overflow: hidden; flex-shrink: 0;">
                @if($user->profile_photo)
                    @if(filter_var($user->profile_photo, FILTER_VALIDATE_URL))
                        <img src="{{ $user->profile_photo }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size: 0.82rem; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</div>
                <div style="font-size: 0.68rem; color: rgba(255,255,255,0.5);">{{ ucfirst($currentRoleDisp) }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- ========= TOPBAR ========= -->
<div class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Menu">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="topbar-title">
            @yield('page-title', 'Dashboard')
        </span>
    </div>

    <div class="topbar-right">
        <!-- Account Dropdown -->
        <div class="dropdown-wrap" id="accountDropdown">
            <button class="account-btn" onclick="toggleAccountDropdown()" aria-label="Akun Saya">
                <div class="avatar">
                    @if($user->profile_photo)
                        @if(filter_var($user->profile_photo, FILTER_VALIDATE_URL))
                            <img src="{{ $user->profile_photo }}" alt="Profile">
                        @else
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile">
                        @endif
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="account-info">
                    <div class="account-name">{{ $user->name }}</div>
                    <div class="account-role">{{ $currentRoleDisp }}</div>
                </div>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div class="dropdown-menu" id="accountMenu">
                <div class="dropdown-header">
                    <div class="dh-name">{{ $user->fullname ?? $user->name }}</div>
                    <div class="dh-nip">{{ session('nip_lama') }}</div>
                </div>

                <a href="{{ route('profile.index') }}" class="dropdown-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>

                @if(count($roleNames) > 1)
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-section-label">Ganti Role</div>
                    @foreach($roleNames as $rid => $rname)
                        @if($rid != session('role'))
                            <form action="{{ route('switch-role', $rid) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    Sebagai {{ ucfirst($rname) }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                @endif

                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="dropdown-item danger">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========= MAIN CONTENT ========= -->
<div class="main-wrapper" id="mainWrapper">
    <div class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- ========= BOTTOM NAVIGATION (Mobile) ========= -->
<nav class="bottom-nav" id="bottomNav">
    <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ $route === 'dashboard' ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Home
    </a>

    @if($isAdmin)
    <a href="{{ route('admin.sessions.index') }}" class="bottom-nav-item {{ str_starts_with($route ?? '', 'admin.sessions') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Kegiatan
    </a>
    <a href="{{ route('admin.scan-qr') }}" class="bottom-nav-item {{ $route === 'admin.scan-qr' ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        Scan QR
    </a>
    <a href="{{ route('admin.reports') }}" class="bottom-nav-item {{ $route === 'admin.reports' ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan
    </a>
    @else
    <a href="{{ route('employee.scan-qr') }}" class="bottom-nav-item {{ $route === 'employee.scan-qr' ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        Scan QR
    </a>
    @endif

    <a href="{{ route('profile.index') }}" class="bottom-nav-item {{ $route === 'profile.index' ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Profil
    </a>
</nav>

@endauth

@stack('scripts')
<script>
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Account Dropdown
    function toggleAccountDropdown() {
        document.getElementById('accountMenu').classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('accountDropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            const menu = document.getElementById('accountMenu');
            if (menu) menu.classList.remove('show');
        }
    });
</script>
</body>
</html>
