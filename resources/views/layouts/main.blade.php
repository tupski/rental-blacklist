<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rental Blacklist') }} - @yield('title', 'Sistem Blacklist Rental')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #da3544;
            --primary-dark: #b82d3c;
            --primary-light: #e85566;
            --primary-gradient: linear-gradient(135deg, #da3544 0%, #b82d3c 100%);
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        /* Modern Navbar Styles */
        .navbar-modern {
            background: var(--primary-gradient) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(218, 53, 68, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0;
        }

        .navbar-modern .navbar-brand {
            font-size: 1.75rem;
            font-weight: 700;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-modern .navbar-brand:hover {
            transform: translateY(-1px);
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-modern .navbar-brand i {
            background: linear-gradient(45deg, #fff, #f8f9fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .navbar-modern .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 0.25rem;
        }

        .navbar-modern .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .navbar-modern .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-modern .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .navbar-modern .btn-outline-light:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-modern .btn-light {
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
        }

        .navbar-modern .btn-light:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dropdown Styles */
        .navbar-modern .dropdown-menu {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .navbar-modern .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: var(--dark-color);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-modern .dropdown-item:hover {
            background: var(--light-color);
            color: var(--primary-color);
            padding-left: 2rem;
        }

        .navbar-modern .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0, 0, 0, 0.1);
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .navbar-modern .navbar-collapse {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin-top: 1rem;
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            .navbar-modern .nav-link {
                color: var(--dark-color) !important;
                margin: 0.25rem 0;
            }

            .navbar-modern .nav-link:hover {
                background: var(--light-color);
                color: var(--primary-color) !important;
            }

            .navbar-modern .nav-link.active {
                background: var(--primary-color);
                color: white !important;
            }
        }

        /* Primary Button Styles */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(218, 53, 68, 0.3);
        }

        .btn-danger {
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(218, 53, 68, 0.3);
        }

        /* Color Utilities */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background: var(--primary-gradient) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .text-danger {
            color: var(--primary-color) !important;
        }

        .bg-danger {
            background: var(--primary-gradient) !important;
        }

        .badge.bg-danger {
            background: var(--primary-gradient) !important;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Card Animations */
        .hover-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Background Gradients */
        .bg-gradient-to-br {
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 50%, #fff7ed 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 50%, #f3e5f5 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #e1f5fe 0%, #ffffff 50%, #e8f5e8 100%);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Footer Styles */
        .footer-modern {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            margin-top: auto;
        }

        .footer-modern h4, .footer-modern h5 {
            color: white;
        }

        .footer-modern .text-danger {
            color: var(--primary-color) !important;
        }

        .footer-modern a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-modern a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-modern .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .footer-modern .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
        }
    </style>

    @yield('meta')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('beranda') }}">
                <i class="fas fa-shield-alt me-2"></i>
                {{ $globalSettings['site_name'] ?? 'RentalGuard' }}
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('beranda') ? 'active fw-bold' : '' }}" href="{{ route('beranda') }}">
                            <i class="fas fa-search me-1"></i>
                            Cari Blacklist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('report.*') ? 'active fw-bold' : '' }}" href="{{ route('laporan.buat') }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Lapor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rental.*') ? 'active fw-bold' : '' }}" href="{{ route('rental.daftar') }}">
                            <i class="fas fa-store me-1"></i>
                            Daftar Rental
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('api.*') ? 'active fw-bold' : '' }}" href="{{ route('api.dokumentasi') }}">
                            <i class="fas fa-code me-1"></i>
                            API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sponsor.*') ? 'active fw-bold' : '' }}" href="{{ route('sponsor.indeks') }}">
                            <i class="fas fa-handshake me-1"></i>
                            Sponsor
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dasbor*') ? 'active fw-bold' : '' }}" href="{{ route('dasbor') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>

                <!-- User Menu -->
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                                <span class="badge bg-success ms-2">{{ Auth::user()->getFormattedBalance() }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profil.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Saldo & Kredit</h6></li>
                                <li><a class="dropdown-item" href="{{ route('isi-saldo.indeks') }}">
                                    <i class="fas fa-plus-circle me-2"></i>Topup Saldo
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('saldo.riwayat') }}">
                                    <i class="fas fa-history me-2"></i>Riwayat Saldo
                                </a></li>
                                @if(Auth::user()->email === 'admin@example.com') {{-- Ganti dengan logic admin yang sesuai --}}
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Admin</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.sponsor.indeks') }}">
                                    <i class="fas fa-handshake me-2"></i>Kelola Sponsor
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.isi-saldo.indeks') }}">
                                    <i class="fas fa-credit-card me-2"></i>Kelola Topup
                                </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('keluar') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('masuk') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm ms-2" href="{{ route('daftar') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="footer-modern mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-shield-alt text-danger me-2"></i>
                        {{ $globalSettings['site_name'] ?? 'RentalGuard' }}
                    </h4>
                    <p class="text-light mb-4">
                        {{ $globalSettings['site_tagline'] ?? 'Sistem Blacklist Rental Indonesia yang terpercaya untuk melindungi bisnis rental Anda.' }}
                    </p>
                    <div class="social-links">
                        @if($globalSettings['facebook_url'] ?? false)
                        <a href="{{ $globalSettings['facebook_url'] }}" target="_blank" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if($globalSettings['twitter_url'] ?? false)
                        <a href="{{ $globalSettings['twitter_url'] }}" target="_blank" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if($globalSettings['instagram_url'] ?? false)
                        <a href="{{ $globalSettings['instagram_url'] }}" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($globalSettings['whatsapp_number'] ?? false)
                        <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] }}" target="_blank" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('beranda') }}">Cek Blacklist</a></li>
                        <li class="mb-2"><a href="{{ route('rental.daftar') }}">Daftar Rental</a></li>
                        <li class="mb-2"><a href="{{ route('laporan.buat') }}">Lapor Masalah</a></li>
                        <li class="mb-2"><a href="{{ route('api.dokumentasi') }}">API Access</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3">Bantuan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">FAQ</a></li>
                        <li class="mb-2"><a href="#">Kontak</a></li>
                        <li class="mb-2"><a href="#">Kebijakan Privasi</a></li>
                        <li class="mb-2"><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <div class="mb-3">
                        <i class="fas fa-envelope me-2 text-danger"></i>
                        <a href="mailto:{{ $globalSettings['contact_email'] ?? 'support@rentalguard.id' }}">
                            {{ $globalSettings['contact_email'] ?? 'support@rentalguard.id' }}
                        </a>
                    </div>
                    @if($globalSettings['contact_phone'] ?? false)
                    <div class="mb-3">
                        <i class="fas fa-phone me-2 text-danger"></i>
                        <a href="tel:{{ $globalSettings['contact_phone'] }}">
                            {{ $globalSettings['contact_phone'] }}
                        </a>
                    </div>
                    @endif

                    @if(isset($footerSponsors) && $footerSponsors->count() > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Didukung oleh:</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($footerSponsors as $sponsor)
                                <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                    <img src="{{ $sponsor->logo_url }}"
                                         alt="{{ $sponsor->name }}"
                                         class="img-fluid bg-white rounded p-2"
                                         style="max-height: 40px; max-width: 120px;"
                                         title="{{ $sponsor->name }}">
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('sponsor.kemitraan') }}" class="btn btn-outline-light btn-sm mt-3">
                            <i class="fas fa-plus me-1"></i>
                            Jadi Sponsor
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="my-4 border-light opacity-25">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ $globalSettings['site_name'] ?? 'RentalGuard' }}. Semua hak dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small>Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk komunitas rental Indonesia</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
