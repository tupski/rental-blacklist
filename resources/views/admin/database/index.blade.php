@extends('layouts.admin')

@section('title', 'Database Management')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Database Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Database Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Maintenance Mode Status -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card {{ $maintenanceMode ? 'border-warning' : 'border-success' }}">
                        <div class="card-header {{ $maintenanceMode ? 'bg-warning' : 'bg-success' }} text-white">
                            <h3 class="card-title">
                                <i class="fas fa-{{ $maintenanceMode ? 'exclamation-triangle' : 'check-circle' }} me-2"></i>
                                Status Aplikasi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-1">
                                        @if($maintenanceMode)
                                            <span class="badge bg-warning">MAINTENANCE MODE</span>
                                        @else
                                            <span class="badge bg-success">ONLINE</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-0">
                                        @if($maintenanceMode)
                                            Aplikasi sedang dalam mode maintenance. Hanya admin yang dapat mengakses.
                                        @else
                                            Aplikasi berjalan normal dan dapat diakses oleh semua pengguna.
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($maintenanceMode)
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#disableMaintenanceModal">
                                            <i class="fas fa-play me-2"></i>Nonaktifkan Maintenance
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#enableMaintenanceModal">
                                            <i class="fas fa-pause me-2"></i>Aktifkan Maintenance
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Statistics -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_users']) }}</h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_blacklists']) }}</h3>
                            <p>Total Blacklists</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_topups']) }}</h3>
                            <p>Total Topups</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['database_size'] }}</h3>
                            <p>Database Size</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-database"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Statistics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users me-2"></i>User Statistics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-user-shield"></i>
                                        </span>
                                        <h5 class="description-header">{{ number_format($stats['admin_users']) }}</h5>
                                        <span class="description-text">Admin Users</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-primary">
                                            <i class="fas fa-car"></i>
                                        </span>
                                        <h5 class="description-header">{{ number_format($stats['rental_users']) }}</h5>
                                        <span class="description-text">Rental Owners</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="description-block">
                                        <span class="description-percentage text-info">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <h5 class="description-header">{{ number_format($stats['regular_users']) }}</h5>
                                        <span class="description-text">Regular Users</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar me-2"></i>System Statistics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-warning">
                                            <i class="fas fa-exchange-alt"></i>
                                        </span>
                                        <h5 class="description-header">{{ number_format($stats['total_transactions']) }}</h5>
                                        <span class="description-text">Transactions</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-handshake"></i>
                                        </span>
                                        <h5 class="description-header">{{ number_format($stats['total_sponsors']) }}</h5>
                                        <span class="description-text">Sponsors</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="description-block">
                                        <span class="description-percentage text-info">
                                            <i class="fas fa-hdd"></i>
                                        </span>
                                        <h5 class="description-header">{{ $stats['storage_size'] }}</h5>
                                        <span class="description-text">Storage Size</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                                Operasi di bawah ini akan menghapus semua data dari database. 
                                Pastikan Anda telah membuat backup sebelum melanjutkan.
                            </div>
                            
                            <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#resetDatabaseModal">
                                <i class="fas fa-trash-alt me-2"></i>Reset Database
                            </button>
                            <p class="text-muted mt-2">
                                Menghapus semua data kecuali akun admin dan mengatur ulang aplikasi ke kondisi fresh install.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
