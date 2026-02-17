<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Presensi BPS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link.active {
            color: var(--primary);
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
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

        /* Cards */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        input, select, textarea, .input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s;
            background: var(--bg-card);
            color: var(--text);
        }

        input:focus, select:focus, textarea:focus, .input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Glassmorphism Classes */
        .glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 0.5s ease forwards;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Tablet & Mobile */
        @media (max-width: 768px) {
            /* Container */
            .container {
                padding: 1rem;
            }

            /* Navbar */
            .navbar {
                padding: 1rem;
                flex-wrap: wrap;
            }

            .logo {
                font-size: 1.2rem;
            }

            /* Mobile Menu Toggle */
            .mobile-menu-toggle {
                display: block;
            }

            /* Navigation Links */
            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 0;
                margin-top: 1rem;
                background: white;
                border-radius: var(--radius-sm);
                padding: 0.5rem 0;
                box-shadow: var(--shadow);
            }

            .nav-links.active {
                display: flex;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                width: 100%;
                border-bottom: 1px solid var(--border);
            }

            .nav-link:last-child {
                border-bottom: none;
            }

            /* Buttons */
            .btn {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                width: 100%;
                text-align: center;
            }

            /* Cards */
            .card {
                padding: 1rem;
            }

            /* Grid Layouts */
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }

            /* Stats Cards - 2 columns on mobile */
            .grid[style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            /* Forms */
            input, select, textarea {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            /* Tables */
            table {
                font-size: 0.85rem;
            }

            thead th {
                padding: 0.5rem 0.25rem;
                font-size: 0.8rem;
            }

            tbody td {
                padding: 0.5rem 0.25rem;
                font-size: 0.85rem;
            }

            /* Quick Actions */
            .quick-actions {
                grid-template-columns: 1fr !important;
            }

            /* QR Code Display */
            #qrcode {
                width: 250px !important;
                height: 250px !important;
            }

            /* Modal */
            .modal-content {
                width: 95% !important;
                margin: 1rem !important;
                padding: 1.5rem !important;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .container {
                padding: 0.75rem;
            }

            .navbar {
                padding: 0.75rem;
            }

            .logo {
                font-size: 1rem;
            }

            /* Stats Cards - 1 column on small mobile */
            .grid[style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: 1fr !important;
            }

            /* Buttons */
            .btn {
                padding: 0.65rem 0.85rem;
                font-size: 0.9rem;
            }

            /* Cards */
            .card {
                padding: 0.85rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            h3 {
                font-size: 1.1rem;
            }

            /* Tables - Horizontal Scroll */
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                min-width: 600px;
            }

            /* QR Code */
            #qrcode {
                width: 200px !important;
                height: 200px !important;
            }
        }

        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            /* Larger touch targets */
            .btn, .nav-link, button, a {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            /* Remove hover effects on touch devices */
            .btn:hover, .nav-link:hover, .card:hover {
                transform: none;
            }

            /* Add active states instead */
            .btn:active {
                transform: scale(0.98);
                opacity: 0.8;
            }
        }

        /* Utilities */
        .flex { display: flex; }
        .grid { display: grid; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-4 { gap: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .text-center { text-align: center; }

    </style>
    @stack('styles')
</head>
<body>
    @auth
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="url(#paint0_linear)"/>
                <path d="M16 8L22 14V22C22 23.1046 21.1046 24 20 24H12C10.8954 24 10 23.1046 10 22V14L16 8Z" fill="white"/>
                <defs>
                    <linearGradient id="paint0_linear" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#6366F1"/>
                        <stop offset="1" stop-color="#4F46E5"/>
                    </linearGradient>
                </defs>
            </svg>
            Presensi BPS
        </a>
        
        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <div class="nav-links" id="navLinks">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            @if(in_array(1, session('roles', [])) || Auth::user()->username == 'admin')
                <a href="{{ route('admin.sessions.index') }}" class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">Kegiatan</a>
                <a href="{{ route('admin.scan-qr') }}" class="nav-link {{ request()->routeIs('admin.scan-qr') ? 'active' : '' }}">Scan QR</a>
                <a href="{{ route('admin.show-qr') }}" class="nav-link {{ request()->routeIs('admin.show-qr') ? 'active' : '' }}">Show QR</a>
                <a href="{{ route('admin.reports') }}" class="nav-link">Laporan</a>
            @endif
            <div class="flex items-center gap-4">
                <span style="font-size: 0.85rem; color: var(--text-muted)">Hi, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <main class="container">
        @if(session('success'))
            <div class="card" style="background: #ecfdf5; border-color: #34d399; color: #065f46; margin-bottom: 1.5rem; padding: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="card" style="background: #fef2f2; border-color: #f87171; color: #991b1b; margin-bottom: 1.5rem; padding: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
    
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.getElementById('navLinks');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (navLinks && menuToggle) {
                if (!navLinks.contains(event.target) && !menuToggle.contains(event.target)) {
                    navLinks.classList.remove('active');
                }
            }
        });
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navLinks = document.getElementById('navLinks');
                if (navLinks) {
                    navLinks.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
