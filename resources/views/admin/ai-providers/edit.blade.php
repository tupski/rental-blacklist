@extends('layouts.admin')

@section('title', 'Edit AI Provider')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit AI Provider</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai-providers.index') }}">AI Providers</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alerts -->
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

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-2"></i>
                                Edit {{ $aiProvider->display_name }}
                            </h3>
                        </div>
                        <form action="{{ route('admin.ai-providers.update', $aiProvider) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name">Display Name</label>
                                            <input type="text" 
                                                   class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" 
                                                   name="display_name" 
                                                   value="{{ old('display_name', $aiProvider->display_name) }}" 
                                                   required>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model">Model</label>
                                            <input type="text" 
                                                   class="form-control @error('model') is-invalid @enderror" 
                                                   id="model" 
                                                   name="model" 
                                                   value="{{ old('model', $aiProvider->model) }}" 
                                                   required>
                                            @error('model')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="api_key">API Key</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('api_key') is-invalid @enderror" 
                                               id="api_key" 
                                               name="api_key" 
                                               value="{{ old('api_key', $aiProvider->api_key) }}" 
                                               required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-api-key">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="endpoint">API Endpoint</label>
                                    <input type="url" 
                                           class="form-control @error('endpoint') is-invalid @enderror" 
                                           id="endpoint" 
                                           name="endpoint" 
                                           value="{{ old('endpoint', $aiProvider->endpoint) }}" 
                                           required>
                                    @error('endpoint')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="daily_limit">Daily Limit</label>
                                            <input type="number" 
                                                   class="form-control @error('daily_limit') is-invalid @enderror" 
                                                   id="daily_limit" 
                                                   name="daily_limit" 
                                                   value="{{ old('daily_limit', $aiProvider->daily_limit) }}" 
                                                   min="1" 
                                                   required>
                                            @error('daily_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="monthly_limit">Monthly Limit</label>
                                            <input type="number" 
                                                   class="form-control @error('monthly_limit') is-invalid @enderror" 
                                                   id="monthly_limit" 
                                                   name="monthly_limit" 
                                                   value="{{ old('monthly_limit', $aiProvider->monthly_limit) }}" 
                                                   min="1" 
                                                   required>
                                            @error('monthly_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <select class="form-control @error('priority') is-invalid @enderror" 
                                                    id="priority" 
                                                    name="priority" 
                                                    required>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" {{ old('priority', $aiProvider->priority) == $i ? 'selected' : '' }}>
                                                        {{ $i }} {{ $i == 1 ? '(Highest)' : ($i == 10 ? '(Lowest)' : '') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $aiProvider->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active Provider
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Update Provider
                                        </button>
                                        <a href="{{ route('admin.ai-providers.index') }}" class="btn btn-secondary ml-2">
                                            <i class="fas fa-arrow-left mr-2"></i>Back
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-info test-provider" data-provider-id="{{ $aiProvider->id }}">
                                            <i class="fas fa-vial mr-2"></i>Test Connection
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>
                                Provider Info
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Provider:</strong></td>
                                    <td>{{ $aiProvider->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Current Usage:</strong></td>
                                    <td>{{ number_format($aiProvider->daily_usage) }} / {{ number_format($aiProvider->daily_limit) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Monthly Usage:</strong></td>
                                    <td>{{ number_format($aiProvider->monthly_usage) }} / {{ number_format($aiProvider->monthly_limit) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Used:</strong></td>
                                    <td>
                                        @if($aiProvider->last_used_at)
                                            {{ $aiProvider->last_used_at->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($aiProvider->is_active && $aiProvider->isAvailable())
                                            <span class="badge badge-success">Available</span>
                                        @elseif($aiProvider->is_active)
                                            <span class="badge badge-warning">Limited</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs mr-2"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-warning btn-block toggle-status" data-provider-id="{{ $aiProvider->id }}">
                                <i class="fas fa-power-off mr-2"></i>
                                {{ $aiProvider->is_active ? 'Disable' : 'Enable' }} Provider
                            </button>
                            <button type="button" class="btn btn-secondary btn-block reset-usage" data-provider-id="{{ $aiProvider->id }}">
                                <i class="fas fa-undo mr-2"></i>Reset Usage Counters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle API key visibility
    $('#toggle-api-key').click(function() {
        const input = $('#api_key');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Test provider connection
    $('.test-provider').click(function() {
        const providerId = $(this).data('provider-id');
        const btn = $(this);
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Testing...').prop('disabled', true);
        
        $.post(`/admin/ai-providers/${providerId}/test`)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
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
});
</script>
@endpush
