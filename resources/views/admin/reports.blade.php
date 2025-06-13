@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<!-- Filter Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form id="reportFilter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Tanggal Dari</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Tanggal Sampai</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report_type">Jenis Laporan</label>
                                <select class="form-control" id="report_type" name="report_type">
                                    <option value="all">Semua</option>
                                    <option value="blacklist">Blacklist</option>
                                    <option value="users">Users</option>
                                    <option value="topup">Topup</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="generateReport()">
                                        <i class="fas fa-search"></i> Generate
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="exportReport()">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="total-blacklist">{{ \App\Models\RentalBlacklist::count() }}</h3>
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
                <h3 id="total-users">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</h3>
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
                <h3 id="pending-reports">{{ \App\Models\GuestReport::where('status', 'pending')->count() }}</h3>
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
                <h3 id="pending-topups">{{ \App\Models\TopupRequest::where('status', 'pending')->count() }}</h3>
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
                <h3 class="card-title">Blacklist per Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="blacklistChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registrasi User per Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="userChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Detail</h3>
            </div>
            <div class="card-body">
                <div id="reportContent">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Pilih filter dan klik Generate untuk melihat laporan</h5>
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
    
    // Set default dates
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    $('#date_from').val(firstDay.toISOString().split('T')[0]);
    $('#date_to').val(today.toISOString().split('T')[0]);
});

function initializeCharts() {
    // Blacklist Chart
    const blacklistCtx = document.getElementById('blacklistChart').getContext('2d');
    new Chart(blacklistCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Blacklist',
                data: [12, 19, 3, 5, 2, 3, 10, 15, 8, 12, 6, 9],
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
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'User Baru',
                data: [5, 8, 12, 7, 9, 15, 20, 18, 14, 16, 11, 13],
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
}

function generateReport() {
    const formData = new FormData(document.getElementById('reportFilter'));
    
    // Show loading
    $('#reportContent').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
            <h5 class="text-primary">Generating report...</h5>
        </div>
    `);
    
    // Simulate API call
    setTimeout(() => {
        $('#reportContent').html(`
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-01-15</td>
                            <td>Blacklist</td>
                            <td>5</td>
                            <td><span class="badge badge-success">Valid</span></td>
                        </tr>
                        <tr>
                            <td>2024-01-14</td>
                            <td>User Registration</td>
                            <td>3</td>
                            <td><span class="badge badge-info">Active</span></td>
                        </tr>
                        <tr>
                            <td>2024-01-13</td>
                            <td>Topup</td>
                            <td>8</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `);
    }, 1500);
}

function exportReport() {
    // Simulate export
    const formData = new FormData(document.getElementById('reportFilter'));
    
    // Create download link
    const link = document.createElement('a');
    link.href = '#';
    link.download = 'report_' + new Date().toISOString().split('T')[0] + '.csv';
    
    // Show success message
    toastr.success('Laporan berhasil diexport!');
}
</script>
@endpush
