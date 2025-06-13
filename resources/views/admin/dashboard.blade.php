@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_blacklist'] ?? 0 }}</h3>
                <p>Total Blacklist</p>
            </div>
            <div class="icon">
                <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('admin.blacklist.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_reports'] ?? 0 }}</h3>
                <p>Pending Reports</p>
            </div>
            <div class="icon">
                <i class="fas fa-flag"></i>
            </div>
            <a href="{{ route('admin.guest-reports.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['pending_topups'] ?? 0 }}</h3>
                <p>Pending Topups</p>
            </div>
            <div class="icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <a href="{{ route('admin.topup.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- Recent Blacklist -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i>
                    Recent Blacklist Reports
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Reporter</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBlacklist ?? [] as $blacklist)
                            <tr>
                                <td>{{ $blacklist->nama_lengkap }}</td>
                                <td>{{ $blacklist->jenis_rental }}</td>
                                <td>{{ $blacklist->user->name }}</td>
                                <td>{{ $blacklist->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $blacklist->status_validitas === 'Valid' ? 'success' : 'warning' }}">
                                        {{ $blacklist->status_validitas }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No recent blacklist reports</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.blacklist.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>

        <!-- Guest Reports -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-flag mr-1"></i>
                    Pending Guest Reports
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reporter</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingReports ?? [] as $report)
                            <tr>
                                <td>{{ $report->nama_lengkap }}</td>
                                <td>{{ $report->nama_pelapor }}</td>
                                <td>{{ $report->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.guest-reports.show', $report->id) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No pending reports</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.guest-reports.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
    </section>
    <!-- /.Left col -->

    <!-- Right col -->
    <section class="col-lg-5 connectedSortable">
        <!-- Calendar -->
        <div class="card bg-gradient-success">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendar
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="calendar" style="width: 100%"></div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    System Information
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-server"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">PHP Version</span>
                                <span class="info-box-number">{{ PHP_VERSION }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fab fa-laravel"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Laravel Version</span>
                                <span class="info-box-number">{{ app()->version() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-database"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Database</span>
                                <span class="info-box-number">{{ config('database.default') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.blacklist.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Add Blacklist
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Add User
                        </a>
                    </div>
                    <div class="col-6 mt-2">
                        <a href="{{ route('admin.sponsors.create') }}" class="btn btn-info btn-block">
                            <i class="fas fa-handshake"></i> Add Sponsor
                        </a>
                    </div>
                    <div class="col-6 mt-2">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.Right col -->
</div>
<!-- /.row (main row) -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize calendar
    $('#calendar').datetimepicker({
        format: 'L',
        inline: true
    });
});
</script>
@endpush
