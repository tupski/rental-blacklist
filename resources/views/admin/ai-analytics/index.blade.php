@extends('layouts.admin')

@section('title', 'AI Analytics Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">AI Analytics Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">AI Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Time Range Filter -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="form-inline">
                                <label class="mr-2">Periode:</label>
                                <select name="days" class="form-control mr-2" onchange="this.form.submit()">
                                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                                </select>
                                <button type="button" class="btn btn-info" onclick="refreshData()">
                                    <i class="fas fa-sync mr-1"></i>Refresh
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overview Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($overview['total_processed']) }}</h3>
                            <p>Total Diproses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-robot"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($overview['auto_approved']) }}</h3>
                            <p>Auto Approved</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($overview['pending_review']) }}</h3>
                            <p>Pending Review</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('admin.ai-analytics.moderation-queue') }}" class="small-box-footer">
                            Review Queue <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($overview['auto_rejected']) }}</h3>
                            <p>Auto Rejected</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary">
                            <i class="fas fa-percentage"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">AI Accuracy</span>
                            <span class="info-box-number">{{ number_format($overview['processing_accuracy'] * 100, 1) }}%</span>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: {{ $overview['processing_accuracy'] * 100 }}%"></div>
                            </div>
                            <span class="progress-description">
                                Model performance
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Avg Risk Score</span>
                            <span class="info-box-number">{{ number_format($overview['avg_risk_score'] * 100, 1) }}%</span>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $overview['avg_risk_score'] * 100 }}%"></div>
                            </div>
                            <span class="progress-description">
                                Content risk level
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">High Risk Users</span>
                            <span class="info-box-number">{{ number_format($overview['high_risk_users']) }}</span>
                            <div class="progress">
                                @php
                                    $totalUsers = \App\Models\User::count();
                                    $riskPercentage = $totalUsers > 0 ? ($overview['high_risk_users'] / $totalUsers) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-warning" style="width: {{ min($riskPercentage, 100) }}%"></div>
                            </div>
                            <span class="progress-description">
                                {{ number_format($riskPercentage, 1) }}% of total users
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Model Precision</span>
                            <span class="info-box-number">{{ number_format($modelPerformance['precision'] * 100, 1) }}%</span>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $modelPerformance['precision'] * 100 }}%"></div>
                            </div>
                            <span class="progress-description">
                                Prediction accuracy
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-area mr-1"></i>
                                Moderation Trends
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="moderationTrendsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Risk Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="riskDistributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Analysis -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Content Type Breakdown
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="contentTypeChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-doughnut mr-1"></i>
                                AI Decisions
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="decisionsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent High Risk Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Recent High Risk Content
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.ai-analytics.moderation-queue') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-list mr-1"></i>View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Content Type</th>
                                            <th>Risk Score</th>
                                            <th>AI Decision</th>
                                            <th>Reasoning</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($flaggedContent as $content)
                                        <tr>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($content['content_type']) }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    @php
                                                        $percentage = $content['overall_risk_score'] * 100;
                                                        $color = $percentage >= 80 ? 'bg-danger' : ($percentage >= 60 ? 'bg-warning' : 'bg-info');
                                                    @endphp
                                                    <div class="progress-bar {{ $color }}" style="width: {{ $percentage }}%">
                                                        {{ number_format($percentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $badgeColor = $content['ai_decision'] === 'approve' ? 'success' :
                                                                 ($content['ai_decision'] === 'reject' ? 'danger' :
                                                                 ($content['ai_decision'] === 'flag' ? 'warning' : 'info'));
                                                @endphp
                                                <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($content['ai_decision']) }}</span>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($content['ai_reasoning'], 100) }}</small>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($content['created_at'])->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if(isset($content['admin_decision']) && $content['admin_decision'])
                                                    <span class="badge badge-{{ $content['admin_decision'] === 'approve' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($content['admin_decision']) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No high risk content found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize charts
    initializeModerationTrendsChart();
    initializeRiskDistributionChart();
    initializeContentTypeChart();
    initializeDecisionsChart();
});

function refreshData() {
    location.reload();
}

function initializeModerationTrendsChart() {
    const ctx = document.getElementById('moderationTrendsChart').getContext('2d');
    const trends = @json($trends);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trends.map(t => new Date(t.date).toLocaleDateString('id-ID')),
            datasets: [{
                label: 'Total Processed',
                data: trends.map(t => t.total),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'Approved',
                data: trends.map(t => t.approved),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'Flagged',
                data: trends.map(t => t.flagged),
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }, {
                label: 'Rejected',
                data: trends.map(t => t.rejected),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
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

function initializeRiskDistributionChart() {
    const ctx = document.getElementById('riskDistributionChart').getContext('2d');
    const distribution = @json($riskDistribution);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Low Risk', 'Medium Risk', 'High Risk', 'Critical Risk'],
            datasets: [{
                data: [distribution.low, distribution.medium, distribution.high, distribution.critical],
                backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function initializeContentTypeChart() {
    const ctx = document.getElementById('contentTypeChart').getContext('2d');

    // Get data via AJAX
    $.get('{{ route("admin.ai-analytics.chart-data") }}', {type: 'content_types', days: {{ $days }}})
        .done(function(data) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(data).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{
                        label: 'Content Count',
                        data: Object.values(data),
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
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
        });
}

function initializeDecisionsChart() {
    const ctx = document.getElementById('decisionsChart').getContext('2d');

    // Get data via AJAX
    $.get('{{ route("admin.ai-analytics.chart-data") }}', {type: 'decision_breakdown', days: {{ $days }}})
        .done(function(data) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(data).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
}
</script>
@endpush
