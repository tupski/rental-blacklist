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
        .hover-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .bg-gradient-to-br {
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 50%, #fff7ed 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 50%, #f3e5f5 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #e1f5fe 0%, #ffffff 50%, #e8f5e8 100%);
        }
        .navbar-brand:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }
        .btn {
            transition: all 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
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
    </style>

    @yield('meta')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand fw-bold text-dark" href="{{ route('beranda') }}">
                <i class="fas fa-shield-alt text-danger me-2"></i>
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
                        <a class="nav-link {{ request()->routeIs('report.*') ? 'active fw-bold' : '' }}" href="{{ route('report.create') }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Lapor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rental.*') ? 'active fw-bold' : '' }}" href="{{ route('rental.register') }}">
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
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Saldo & Kredit</h6></li>
                                <li><a class="dropdown-item" href="{{ route('topup.index') }}">
                                    <i class="fas fa-plus-circle me-2"></i>Topup Saldo
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('balance.history') }}">
                                    <i class="fas fa-history me-2"></i>Riwayat Saldo
                                </a></li>
                                @if(Auth::user()->email === 'admin@example.com') {{-- Ganti dengan logic admin yang sesuai --}}
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Admin</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.sponsors.index') }}">
                                    <i class="fas fa-handshake me-2"></i>Kelola Sponsor
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.topup.index') }}">
                                    <i class="fas fa-credit-card me-2"></i>Kelola Topup
                                </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
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
                            <a class="btn btn-danger btn-sm ms-2" href="{{ route('daftar') }}">
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

    <!-- Footer with Sponsors -->
    @if(isset($footerSponsors) && $footerSponsors->count() > 0)
    <footer class="bg-white border-top mt-5">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="text-muted mb-0">Didukung oleh:</h6>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap align-items-center justify-content-md-end gap-3">
                        @foreach($footerSponsors as $sponsor)
                            <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                <img src="{{ $sponsor->logo_url }}"
                                     alt="{{ $sponsor->name }}"
                                     class="img-fluid"
                                     style="max-height: 40px; max-width: 120px;"
                                     title="{{ $sponsor->name }}">
                            </a>
                        @endforeach
                        <a href="{{ route('sponsors.sponsorship') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Jadi Sponsor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @endif

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
