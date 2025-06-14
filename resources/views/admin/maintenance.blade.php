@extends('layouts.admin')

@section('title', 'Maintenance')
@section('page-title', 'Maintenance')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Maintenance</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cache Management</h3>
            </div>
            <div class="card-body">
                <p>Bersihkan cache untuk meningkatkan performa aplikasi.</p>
                
                <form action="{{ route('admin.maintenance.clear-cache') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-broom"></i> Clear All Cache
                    </button>
                </form>
                
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-block" onclick="clearSpecificCache('config')">
                            <i class="fas fa-cog"></i> Config Cache
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-block" onclick="clearSpecificCache('route')">
                            <i class="fas fa-route"></i> Route Cache
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-block" onclick="clearSpecificCache('view')">
                            <i class="fas fa-eye"></i> View Cache
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-block" onclick="clearSpecificCache('event')">
                            <i class="fas fa-calendar"></i> Event Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Optimization</h3>
            </div>
            <div class="card-body">
                <p>Optimasi sistem untuk performa yang lebih baik.</p>
                
                <form action="{{ route('admin.maintenance.optimize') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-rocket"></i> Optimize System
                    </button>
                </form>
                
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success btn-sm btn-block" onclick="runOptimization('autoload')">
                            <i class="fas fa-download"></i> Autoload
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success btn-sm btn-block" onclick="runOptimization('config')">
                            <i class="fas fa-cogs"></i> Config
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success btn-sm btn-block" onclick="runOptimization('route')">
                            <i class="fas fa-map"></i> Routes
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success btn-sm btn-block" onclick="runOptimization('view')">
                            <i class="fas fa-eye"></i> Views
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Database Maintenance</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Database Size</label>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 45%">
                            45% (2.3 GB)
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-info btn-block mb-2" onclick="runDatabaseMaintenance('analyze')">
                    <i class="fas fa-search"></i> Analyze Tables
                </button>
                
                <button type="button" class="btn btn-warning btn-block mb-2" onclick="runDatabaseMaintenance('optimize')">
                    <i class="fas fa-database"></i> Optimize Tables
                </button>
                
                <button type="button" class="btn btn-danger btn-block" onclick="runDatabaseMaintenance('vacuum')">
                    <i class="fas fa-compress"></i> Vacuum Database
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Management</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Log Files Size</label>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%">
                            75% (156 MB)
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-warning btn-block mb-2" onclick="manageLogs('clear')">
                    <i class="fas fa-trash"></i> Clear Old Logs
                </button>
                
                <button type="button" class="btn btn-info btn-block mb-2" onclick="manageLogs('download')">
                    <i class="fas fa-download"></i> Download Logs
                </button>
                
                <button type="button" class="btn btn-secondary btn-block" onclick="manageLogs('rotate')">
                    <i class="fas fa-sync"></i> Rotate Logs
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Laravel Version:</strong></td>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td>{{ PHP_VERSION }}</td>
                            </tr>
                            <tr>
                                <td><strong>Environment:</strong></td>
                                <td>
                                    <span class="badge badge-{{ app()->environment() === 'production' ? 'success' : 'warning' }}">
                                        {{ ucfirst(app()->environment()) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Debug Mode:</strong></td>
                                <td>
                                    <span class="badge badge-{{ config('app.debug') ? 'danger' : 'success' }}">
                                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Server:</strong></td>
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
                            <tr>
                                <td><strong>Upload Max Size:</strong></td>
                                <td>{{ ini_get('upload_max_filesize') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Output Modal -->
<div class="modal fade" id="outputModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Command Output</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="commandOutput" class="bg-dark text-light p-3" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearSpecificCache(type) {
    showLoading();
    
    // Simulate API call
    setTimeout(() => {
        showOutput(`Cache cleared successfully: ${type}`);
        toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} cache cleared!`);
    }, 1000);
}

function runOptimization(type) {
    showLoading();
    
    // Simulate API call
    setTimeout(() => {
        showOutput(`Optimization completed: ${type}`);
        toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} optimized!`);
    }, 1500);
}

function runDatabaseMaintenance(action) {
    showLoading();
    
    // Simulate API call
    setTimeout(() => {
        showOutput(`Database ${action} completed successfully`);
        toastr.success(`Database ${action} completed!`);
    }, 2000);
}

function manageLogs(action) {
    showLoading();
    
    // Simulate API call
    setTimeout(() => {
        if (action === 'download') {
            showOutput('Preparing log download...');
            toastr.info('Log download started!');
        } else {
            showOutput(`Log ${action} completed successfully`);
            toastr.success(`Log ${action} completed!`);
        }
    }, 1000);
}

function showLoading() {
    $('#commandOutput').text('Running command...');
    $('#outputModal').modal('show');
}

function showOutput(message) {
    $('#commandOutput').text(message);
}

// Initialize toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};
</script>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
