@extends('layouts.admin')

@section('title', 'Pengaturan Database')
@section('page-title', 'Pengaturan Database')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">Database</li>
@endsection

@section('content')
<div class="row">
    <!-- Database Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database mr-2"></i>
                    Informasi Database
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama Database:</strong></td>
                        <td>{{ $dbInfo['database_name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Ukuran Database:</strong></td>
                        <td>{{ $dbInfo['database_size'] }} MB</td>
                    </tr>
                    <tr>
                        <td><strong>Total Tabel:</strong></td>
                        <td>{{ $dbInfo['total_tables'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Koneksi:</strong></td>
                        <td>
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Terhubung
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Cache Status -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-memory mr-2"></i>
                    Status Cache
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Config Cache:</strong></td>
                        <td>
                            @if($dbInfo['cache_status']['config'])
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-times"></i> Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Route Cache:</strong></td>
                        <td>
                            @if($dbInfo['cache_status']['routes'])
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-times"></i> Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>View Cache:</strong></td>
                        <td>
                            @if($dbInfo['cache_status']['views'])
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-times"></i> Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Optimization Tools -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i>
                    Tools Optimasi
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Cache Management -->
                    <div class="col-md-4">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-broom mr-2"></i>
                                    Bersihkan Cache
                                </h5>
                                <p class="card-text">
                                    Hapus semua cache aplikasi, config, route, dan view.
                                </p>
                                <form action="{{ route('admin.settings.database.clear-cache') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin ingin membersihkan cache?')">
                                        <i class="fas fa-trash"></i> Bersihkan Cache
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- System Optimization -->
                    <div class="col-md-4">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Optimasi Sistem
                                </h5>
                                <p class="card-text">
                                    Optimasi aplikasi dengan cache config dan route.
                                </p>
                                <form action="{{ route('admin.settings.database.optimize') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mengoptimasi sistem?')">
                                        <i class="fas fa-cog"></i> Optimasi Sistem
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Database Optimization -->
                    <div class="col-md-4">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-database mr-2"></i>
                                    Optimasi Database
                                </h5>
                                <p class="card-text">
                                    Optimasi semua tabel database untuk performa lebih baik.
                                </p>
                                <form action="{{ route('admin.settings.database.optimize-db') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info" onclick="return confirm('Yakin ingin mengoptimasi database?')">
                                        <i class="fas fa-database"></i> Optimasi Database
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Information -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-2"></i>
                    Informasi Server
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td>{{ PHP_VERSION }}</td>
                    </tr>
                    <tr>
                        <td><strong>Laravel Version:</strong></td>
                        <td>{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Server Software:</strong></td>
                        <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Memory Limit:</strong></td>
                        <td>{{ ini_get('memory_limit') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Max Execution Time:</strong></td>
                        <td>{{ ini_get('max_execution_time') }}s</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Statistik Aplikasi
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Total Users:</strong></td>
                        <td>{{ \App\Models\User::count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Blacklist:</strong></td>
                        <td>{{ \App\Models\RentalBlacklist::count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Guest Reports:</strong></td>
                        <td>{{ \App\Models\GuestReport::count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Sponsors:</strong></td>
                        <td>{{ \App\Models\Sponsor::count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Settings:</strong></td>
                        <td>{{ \App\Models\Setting::count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Warning Notice -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan</h5>
            <ul class="mb-0">
                <li><strong>Bersihkan Cache:</strong> Akan menghapus semua cache dan mungkin memperlambat aplikasi sementara.</li>
                <li><strong>Optimasi Sistem:</strong> Akan membuat cache baru untuk mempercepat aplikasi.</li>
                <li><strong>Optimasi Database:</strong> Proses ini mungkin memakan waktu lama tergantung ukuran database.</li>
                <li><strong>Backup:</strong> Selalu lakukan backup database sebelum melakukan optimasi.</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto refresh database info every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
    
    // Confirmation dialogs with more details
    $('form').on('submit', function(e) {
        const action = $(this).attr('action');
        let message = '';
        
        if (action.includes('clear-cache')) {
            message = 'Membersihkan cache akan menghapus semua cache aplikasi. Aplikasi mungkin akan lebih lambat sementara waktu. Lanjutkan?';
        } else if (action.includes('optimize-db')) {
            message = 'Optimasi database akan memakan waktu tergantung ukuran database. Pastikan tidak ada user yang sedang menggunakan sistem. Lanjutkan?';
        } else if (action.includes('optimize')) {
            message = 'Optimasi sistem akan membuat cache baru untuk mempercepat aplikasi. Lanjutkan?';
        }
        
        if (message && !confirm(message)) {
            e.preventDefault();
        }
    });
});
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
