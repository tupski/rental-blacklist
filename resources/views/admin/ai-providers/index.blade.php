@extends('layouts.admin')

@section('title', 'AI Providers Management')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">AI Providers Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">AI Providers</li>
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
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Analytics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $analytics['total_conversations'] ?? 0 }}</h3>
                            <p>Total Conversations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($analytics['avg_response_time'] ?? 0) }}ms</h3>
                            <p>Avg Response Time</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($analytics['total_tokens'] ?? 0) }}</h3>
                            <p>Total Tokens Used</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($analytics['total_cost'] ?? 0, 2) }}</h3>
                            <p>Total Cost</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Providers Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-robot mr-2"></i>
                                AI Providers
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProviderModal">
                                    <i class="fas fa-plus mr-1"></i>Add Provider
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="refresh-stats">
                                    <i class="fas fa-sync mr-1"></i>Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Provider</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                            <th>Usage Today</th>
                                            <th>Monthly Usage</th>
                                            <th>Priority</th>
                                            <th>Last Used</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="providers-table-body">
                                        @foreach($providers as $provider)
                                        <tr data-provider-id="{{ $provider->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="provider-icon mr-2">
                                                        @if($provider->name === 'claude')
                                                            <i class="fas fa-brain text-purple"></i>
                                                        @elseif($provider->name === 'openai')
                                                            <i class="fas fa-robot text-success"></i>
                                                        @elseif($provider->name === 'gemini')
                                                            <i class="fas fa-gem text-warning"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $provider->display_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $provider->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $provider->model }}</code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($provider->is_active && $provider->isAvailable())
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Available
                                                        </span>
                                                    @elseif($provider->is_active)
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>Limited
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress mb-1" style="height: 20px;">
                                                    @php
                                                        $percentage = $provider->daily_limit > 0 ? ($provider->daily_usage / $provider->daily_limit) * 100 : 0;
                                                        $progressClass = $percentage > 80 ? 'bg-danger' : ($percentage > 60 ? 'bg-warning' : 'bg-success');
                                                    @endphp
                                                    <div class="progress-bar {{ $progressClass }}"
                                                         style="width: {{ min($percentage, 100) }}%">
                                                        {{ number_format($percentage, 1) }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ number_format($provider->daily_usage) }} / {{ number_format($provider->daily_limit) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress mb-1" style="height: 20px;">
                                                    @php
                                                        $monthlyPercentage = $provider->monthly_limit > 0 ? ($provider->monthly_usage / $provider->monthly_limit) * 100 : 0;
                                                        $monthlyProgressClass = $monthlyPercentage > 80 ? 'bg-danger' : ($monthlyPercentage > 60 ? 'bg-warning' : 'bg-info');
                                                    @endphp
                                                    <div class="progress-bar {{ $monthlyProgressClass }}"
                                                         style="width: {{ min($monthlyPercentage, 100) }}%">
                                                        {{ number_format($monthlyPercentage, 1) }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ number_format($provider->monthly_usage) }} / {{ number_format($provider->monthly_limit) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $provider->priority }}</span>
                                            </td>
                                            <td>
                                                @if($provider->last_used_at)
                                                    <span title="{{ $provider->last_used_at->format('d/m/Y H:i:s') }}">
                                                        {{ $provider->last_used_at->diffForHumans() }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-info test-provider"
                                                            data-provider-id="{{ $provider->id }}"
                                                            title="Test Connection">
                                                        <i class="fas fa-vial"></i>
                                                    </button>
                                                    <button class="btn btn-warning toggle-status"
                                                            data-provider-id="{{ $provider->id }}"
                                                            title="Toggle Status">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                    <button class="btn btn-secondary reset-usage"
                                                            data-provider-id="{{ $provider->id }}"
                                                            title="Reset Usage">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <a href="{{ route('admin.ai-providers.edit', $provider) }}"
                                                       class="btn btn-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Usage Chart -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Provider Usage Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="providerUsageChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Response Time Trends</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="responseTimeChart" width="400" height="200"></canvas>
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
    // Refresh stats
    $('#refresh-stats').click(function() {
        const btn = $(this);
        const originalText = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading...').prop('disabled', true);

        $.get('/admin/ai-providers-stats')
            .done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    toastr.error('Failed to refresh stats');
                }
            })
            .fail(function() {
                toastr.error('Failed to refresh stats');
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });

    // Test provider connection
    $('.test-provider').click(function() {
        const providerId = $(this).data('provider-id');
        const btn = $(this);
        const originalText = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.post(`/admin/ai-providers/${providerId}/test`)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    if (response.response) {
                        toastr.info('Response: ' + response.response);
                    }
                } else {
                    toastr.error(response.error);
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.error || 'Test failed';
                toastr.error(error);
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });

    // Toggle provider status
    $('.toggle-status').click(function() {
        const providerId = $(this).data('provider-id');
        const btn = $(this);

        $.post(`/admin/ai-providers/${providerId}/toggle-status`)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(response.error);
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.error || 'Failed to toggle status';
                toastr.error(error);
            });
    });

    // Reset usage counters
    $('.reset-usage').click(function() {
        const providerId = $(this).data('provider-id');

        if (confirm('Are you sure you want to reset usage counters?')) {
            $.post(`/admin/ai-providers/${providerId}/reset-usage`)
                .done(function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.error);
                    }
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON?.error || 'Failed to reset usage';
                    toastr.error(error);
                });
        }
    });

    // Initialize charts if data available
    @if(isset($analytics['provider_usage']) && !empty($analytics['provider_usage']))
    // Provider Usage Chart
    const providerCtx = document.getElementById('providerUsageChart');
    if (providerCtx) {
        new Chart(providerCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($analytics['provider_usage'])) !!},
                datasets: [{
                    data: {!! json_encode(array_values($analytics['provider_usage'])) !!},
                    backgroundColor: ['#da3544', '#28a745', '#ffc107', '#17a2b8', '#6f42c1']
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
    @endif

    // Response Time Chart (placeholder)
    const responseCtx = document.getElementById('responseTimeChart');
    if (responseCtx) {
        new Chart(responseCtx, {
            type: 'line',
            data: {
                labels: ['1h ago', '45m ago', '30m ago', '15m ago', 'Now'],
                datasets: [{
                    label: 'Response Time (ms)',
                    data: [{{ $analytics['avg_response_time'] ?? 1000 }}, 950, 1100, 800, {{ $analytics['avg_response_time'] ?? 1000 }}],
                    borderColor: '#da3544',
                    backgroundColor: 'rgba(218, 53, 68, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Response Time (ms)'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
