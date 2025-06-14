<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') | {{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')

    <!-- Custom Admin Styles -->
    <style>
        :root {
            --primary-color: #da3544;
            --primary-dark: #b82d3c;
            --primary-light: #e85566;
            --primary-gradient: linear-gradient(135deg, #da3544 0%, #b82d3c 100%);
        }

        /* AdminLTE Customization */
        .main-header.navbar {
            background: var(--primary-gradient) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-header .navbar-brand {
            color: white !important;
            font-weight: 700;
        }

        .main-header .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .main-header .nav-link:hover {
            color: white !important;
        }

        .main-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }

        .main-sidebar .brand-link {
            background: rgba(218, 53, 68, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-sidebar .brand-text {
            color: white !important;
            font-weight: 700;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: rgba(218, 53, 68, 0.3) !important;
            color: white !important;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(218, 53, 68, 0.3);
        }

        .btn-danger {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
        }

        .btn-danger:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Cards */
        .card-primary .card-header {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
        }

        .card-danger .card-header {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
        }

        /* Small Boxes */
        .small-box.bg-primary {
            background: var(--primary-gradient) !important;
        }

        .small-box.bg-danger {
            background: var(--primary-gradient) !important;
        }

        /* Text Colors */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-danger {
            color: var(--primary-color) !important;
        }

        /* Badges */
        .badge-primary {
            background: var(--primary-gradient);
        }

        .badge-danger {
            background: var(--primary-gradient);
        }

        /* Footer */
        .main-footer.bg-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-footer .text-danger {
            color: var(--primary-color) !important;
        }

        /* Animations */
        .btn {
            transition: all 0.3s ease;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .small-box {
            transition: transform 0.2s ease;
        }

        .small-box:hover {
            transform: translateY(-2px);
        }

        /* Notification Dropdown */
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .dropdown-item:hover {
            background-color: rgba(218, 53, 68, 0.1);
            color: var(--primary-color);
        }

        /* Content Header */
        .content-header h1 {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Breadcrumb */
        .breadcrumb-item.active {
            color: var(--primary-color);
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('images/logo.svg') }}" alt="Logo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark"
         style="background: linear-gradient(135deg, #da3544 0%, #b82d3c 100%);"&gt;
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dasbor') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('beranda') }}" class="nav-link" target="_blank">View Site</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" id="notificationDropdown">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="notificationCount">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header" id="notificationHeader">0 Notifikasi</span>
                    <div class="dropdown-divider"></div>
                    <div id="notificationList">
                        <div class="dropdown-item text-center text-muted">
                            <i class="fas fa-bell-slash mr-2"></i>Tidak ada notifikasi
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.notifikasi.indeks') }}" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                </div>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-header">
                        <strong>{{ auth()->user()->name }}</strong><br>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profil.edit') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="{{ route('admin.pengaturan.indeks') }}" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('keluar') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dasbor') }}" class="brand-link">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=007bff&color=fff" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('profil.edit') }}" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('admin.dasbor') }}" class="nav-link {{ request()->routeIs('admin.dasbor*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Manajemen Blacklist -->
                    <li class="nav-item {{ request()->routeIs('admin.daftar-hitam*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.daftar-hitam*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ban"></i>
                            <p>
                                Manajemen Blacklist
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.daftar-hitam.indeks') }}" class="nav-link {{ request()->routeIs('admin.daftar-hitam.indeks') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Blacklist</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.daftar-hitam.buat') }}" class="nav-link {{ request()->routeIs('admin.daftar-hitam.buat') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Blacklist</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Manajemen User -->
                    <li class="nav-item {{ request()->routeIs('admin.pengguna*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Manajemen User
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.pengguna.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengguna.indeks') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pengguna.buat') }}" class="nav-link {{ request()->routeIs('admin.pengguna.buat') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tambah User</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Laporan Guest -->
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan-tamu.indeks') }}" class="nav-link {{ request()->routeIs('admin.laporan-tamu*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-flag"></i>
                            <p>Laporan Guest</p>
                        </a>
                    </li>

                    <!-- Manajemen Sponsor -->
                    <li class="nav-item {{ request()->routeIs('admin.sponsors*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.sponsors*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>
                                Manajemen Sponsor
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.sponsor.indeks') }}" class="nav-link {{ request()->routeIs('admin.sponsor.indeks') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Sponsor</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.sponsor.buat') }}" class="nav-link {{ request()->routeIs('admin.sponsor.buat') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Sponsor</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Manajemen Topup -->
                    <li class="nav-item">
                        <a href="{{ route('admin.isi-saldo.indeks') }}" class="nav-link {{ request()->routeIs('admin.isi-saldo*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Manajemen Topup</p>
                        </a>
                    </li>

                    <!-- Laporan & Analitik -->
                    <li class="nav-item {{ request()->routeIs('admin.reports*') || request()->routeIs('admin.analytics*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.reports*') || request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Laporan & Analitik
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.analitik') }}" class="nav-link {{ request()->routeIs('admin.analitik') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Analitik</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Pengaturan -->
                    <li class="nav-item {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.maintenance*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.maintenance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Pengaturan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.pengaturan.aplikasi.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.aplikasi*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aplikasi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pengaturan.sistem.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.sistem*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sistem</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pengaturan.smtp.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.smtp*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>SMTP</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pengaturan.pembayaran.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.pembayaran*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pembayaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pengaturan.database.indeks') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.database*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Database</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.maintenance') }}" class="nav-link {{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Maintenance</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-ban"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <!-- Modern Footer -->
    <footer class="main-footer" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
        <div class="container-fluid">
            <div class="row py-3">
                <div class="col-md-6">
                    <strong class="text-white">
                        <i class="fas fa-shield-alt text-danger me-2"></i>
                        Copyright &copy; {{ date('Y') }}
                        <a href="{{ route('beranda') }}" class="text-danger font-weight-bold">{{ config('app.name') }}</a>
                    </strong>
                    <br>
                    <small class="text-light">Semua hak dilindungi undang-undang.</small>
                </div>
                <div class="col-md-6 text-md-right">
                    <div class="mb-2">
                        <small class="text-light font-weight-medium">Admin Panel</small>
                        <span class="badge badge-danger ml-2 px-2 py-1">v1.0.0</span>
                    </div>
                    <div>
                        <small class="text-light">
                            Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk komunitas rental Indonesia
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')

<!-- Notification System -->
<script>
$(document).ready(function() {
    // Load notifications on page load
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    function loadNotifications() {
        $.ajax({
            url: '{{ route("admin.notifikasi.ambil") }}',
            method: 'GET',
            success: function(response) {
                updateNotificationUI(response);
            },
            error: function() {
                console.log('Error loading notifications');
            }
        });
    }

    function updateNotificationUI(data) {
        const count = data.unread_count || 0;
        const notifications = data.notifications || [];

        // Update badge
        $('#notificationCount').text(count);
        if (count > 0) {
            $('#notificationCount').show();
        } else {
            $('#notificationCount').hide();
        }

        // Update header
        $('#notificationHeader').text(count + ' Notifikasi');

        // Update notification list
        let notificationHtml = '';
        if (notifications.length > 0) {
            notifications.forEach(function(notification) {
                notificationHtml += `
                    <a href="#" class="dropdown-item ${notification.read_at ? '' : 'bg-light'}">
                        <i class="${getNotificationIcon(notification.type)} mr-2"></i>
                        ${notification.data.message}
                        <span class="float-right text-muted text-sm">${formatTime(notification.created_at)}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                `;
            });
        } else {
            notificationHtml = `
                <div class="dropdown-item text-center text-muted">
                    <i class="fas fa-bell-slash mr-2"></i>Tidak ada notifikasi
                </div>
            `;
        }

        $('#notificationList').html(notificationHtml);
    }

    function getNotificationIcon(type) {
        switch(type) {
            case 'App\\Notifications\\UserRegisteredNotification':
                return 'fas fa-user-plus text-success';
            case 'App\\Notifications\\TopupRequestNotification':
                return 'fas fa-credit-card text-info';
            case 'App\\Notifications\\NewTopupNotification':
                return 'fas fa-credit-card text-warning';
            case 'App\\Notifications\\TopupStatusNotification':
                return 'fas fa-check-circle text-success';
            case 'App\\Notifications\\BlacklistReportNotification':
                return 'fas fa-ban text-danger';
            default:
                return 'fas fa-bell text-primary';
        }
    }

    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return diff + 's';
        if (diff < 3600) return Math.floor(diff / 60) + 'm';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h';
        return Math.floor(diff / 86400) + 'd';
    }

    // Mark notification as read when clicked
    $(document).on('click', '.dropdown-item[data-notification-id]', function() {
        const notificationId = $(this).data('notification-id');
        $.ajax({
            url: '{{ route("admin.notifikasi.baca") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                notification_id: notificationId
            },
            success: function() {
                loadNotifications();
            }
        });
    });
});
</script>

</body>
</html>
