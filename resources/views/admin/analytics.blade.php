@extends('layouts.admin')

@section('title', 'Analitik')
@section('page-title', 'Analitik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Analitik</li>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $data['total_blacklists'] }}</h3>
                <p>Total Blacklist</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $data['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $data['pending_reports'] }}</h3>
                <p>Laporan Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $data['pending_topups'] }}</h3>
                <p>Topup Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blacklist per Bulan (12 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <canvas id="blacklistChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registrasi User per Bulan (12 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <canvas id="userChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blacklist berdasarkan Jenis Rental</h3>
            </div>
            <div class="card-body">
                <canvas id="blacklistTypeChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User berdasarkan Role</h3>
            </div>
            <div class="card-body">
                <canvas id="userRoleChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 10 Blacklist Terbaru</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Jenis</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\RentalBlacklist::latest()->limit(10)->get() as $blacklist)
                            <tr>
                                <td>{{ $blacklist->nama_lengkap }}</td>
                                <td>{{ $blacklist->nik }}</td>
                                <td><span class="badge badge-info">{{ $blacklist->jenis_rental }}</span></td>
                                <td>{{ $blacklist->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Terbaru</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::where('role', '!=', 'admin')->latest()->limit(10)->get() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'pengusaha_rental')
                                        <span class="badge badge-warning">Rental</span>
                                    @else
                                        <span class="badge badge-info">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Metrik Performa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tingkat Validasi</span>
                                <span class="info-box-number">
                                    {{ \App\Models\RentalBlacklist::count() > 0 ? round((\App\Models\RentalBlacklist::where('status_validitas', 'Valid')->count() / \App\Models\RentalBlacklist::count()) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">User Terverifikasi</span>
                                <span class="info-box-number">
                                    {{ \App\Models\User::whereNotNull('email_verified_at')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Rata-rata Harian</span>
                                <span class="info-box-number">
                                    {{ round(\App\Models\RentalBlacklist::whereDate('created_at', '>=', now()->subDays(30))->count() / 30, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Review</span>
                                <span class="info-box-number">
                                    {{ \App\Models\RentalBlacklist::where('status_validitas', 'Pending')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize charts
    initializeCharts();
});

function initializeCharts() {
    // Blacklist Chart
    const blacklistCtx = document.getElementById('blacklistChart').getContext('2d');
    new Chart(blacklistCtx, {
        type: 'line',
        data: {
            labels: getMonthLabels(),
            datasets: [{
                label: 'Blacklist',
                data: @json($data['monthly_blacklists']),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: getMonthLabels(),
            datasets: [{
                label: 'User Baru',
                data: @json($data['monthly_users']),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Blacklist Type Chart
    const blacklistTypeCtx = document.getElementById('blacklistTypeChart').getContext('2d');
    new Chart(blacklistTypeCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(@json($data['blacklist_by_type'])),
            datasets: [{
                data: Object.values(@json($data['blacklist_by_type'])),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // User Role Chart
    const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(userRoleCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(@json($data['user_by_role'])),
            datasets: [{
                data: Object.values(@json($data['user_by_role'])),
                backgroundColor: [
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function getMonthLabels() {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const labels = [];
    const now = new Date();
    
    for (let i = 11; i >= 0; i--) {
        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
        labels.push(months[date.getMonth()]);
    }
    
    return labels;
}
</script>
@endpush
