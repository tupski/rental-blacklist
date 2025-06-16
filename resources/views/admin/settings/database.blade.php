@extends('layouts.admin')

@section('title', 'Pengaturan Database')
@section('page-title', 'Pengaturan Database')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">Database</li>
@endsection

@section('content')
<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}

        @if(session('bypass_url'))
            <hr>
            <h6><i class="fas fa-key mr-2"></i>Link Bypass Maintenance:</h6>
            <div class="input-group mt-2">
                <input type="text" class="form-control" id="bypassUrl" value="{{ session('bypass_url') }}" readonly>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyBypassUrl()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <a href="{{ session('bypass_url') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Buka
                    </a>
                </div>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Gunakan link ini untuk mengakses admin panel saat maintenance mode aktif.
            </small>
        @endif

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
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
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Maintenance Mode Status -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card {{ $maintenanceMode ? 'border-warning' : 'border-success' }}">
            <div class="card-header {{ $maintenanceMode ? 'bg-warning' : 'bg-success' }}">
                <h3 class="card-title text-white">
                    <i class="fas fa-{{ $maintenanceMode ? 'exclamation-triangle' : 'check-circle' }} mr-2"></i>
                    Status Aplikasi
                </h3>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">
                            @if($maintenanceMode)
                                <span class="badge badge-warning">MAINTENANCE MODE</span>
                            @else
                                <span class="badge badge-success">ONLINE</span>
                            @endif
                        </h5>
                        <p class="text-muted mb-0">
                            @if($maintenanceMode)
                                Aplikasi sedang dalam mode maintenance. Hanya admin yang dapat mengakses.
                                @if($currentSecret)
                                    <br><small class="text-info">
                                        <i class="fas fa-key mr-1"></i>
                                        Secret Key: <code>{{ $currentSecret }}</code>
                                    </small>
                                @endif
                            @else
                                Aplikasi berjalan normal dan dapat diakses oleh semua pengguna.
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        @if($maintenanceMode)
                            <div class="btn-group-vertical" role="group">
                                @if($currentSecret)
                                    <button type="button" class="btn btn-info btn-sm mb-2" onclick="copyCurrentSecret()">
                                        <i class="fas fa-copy mr-1"></i>Copy Secret Key
                                    </button>
                                    <a href="{{ url('/?secret=' . $currentSecret) }}" class="btn btn-primary btn-sm mb-2" target="_blank">
                                        <i class="fas fa-external-link-alt mr-1"></i>Buka Bypass Link
                                    </a>
                                @endif
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#disableMaintenanceModal">
                                    <i class="fas fa-play mr-2"></i>Nonaktifkan Maintenance
                                </button>
                            </div>
                        @else
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#enableMaintenanceModal">
                                <i class="fas fa-pause mr-2"></i>Aktifkan Maintenance
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                                <form action="{{ route('admin.pengaturan.database.bersihkan-cache') }}" method="POST" class="d-inline">
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
                                <form action="{{ route('admin.pengaturan.database.optimasi') }}" method="POST" class="d-inline">
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
                                <form action="{{ route('admin.pengaturan.database.optimasi-db') }}" method="POST" class="d-inline">
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

<!-- Danger Zone -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger">
                <h3 class="card-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                    Operasi di bawah ini akan menghapus semua data dari database.
                    Pastikan Anda telah membuat backup sebelum melanjutkan.
                </div>

                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#resetDatabaseModal">
                    <i class="fas fa-trash-alt mr-2"></i>Reset Database
                </button>
                <p class="text-muted mt-2">
                    Menghapus semua data kecuali akun admin dan mengatur ulang aplikasi ke kondisi fresh install.
                </p>
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
<!-- Reset Database Modal -->
<div class="modal fade" id="resetDatabaseModal" tabindex="-1" role="dialog" aria-labelledby="resetDatabaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title" id="resetDatabaseModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Reset Database
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pengaturan.database.reset') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-skull-crossbones mr-2"></i>PERINGATAN KERAS!</h5>
                        <p class="mb-0">
                            Operasi ini akan <strong>MENGHAPUS SEMUA DATA</strong> dari database kecuali akun admin.
                            Pastikan Anda sudah membuat backup database sebelum melanjutkan.
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="confirmation_1" class="form-label">Ketik "RESET":</label>
                            <input type="text" class="form-control" id="confirmation_1" name="confirmation_1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="confirmation_2" class="form-label">Ketik "DATABASE":</label>
                            <input type="text" class="form-control" id="confirmation_2" name="confirmation_2" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="confirmation_3" class="form-label">Ketik "CONFIRM":</label>
                            <input type="text" class="form-control" id="confirmation_3" name="confirmation_3" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password Admin:</label>
                        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        <small class="form-text text-muted">Masukkan password admin untuk konfirmasi</small>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-2"></i>Yang akan dihapus:</h6>
                        <ul class="mb-0">
                            <li>Semua data users (kecuali admin)</li>
                            <li>Semua data blacklist</li>
                            <li>Semua data topup dan transaksi</li>
                            <li>Semua data sponsor</li>
                            <li>Semua file upload</li>
                            <li>Settings akan direset ke default</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-2"></i>Reset Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enable Maintenance Modal -->
<div class="modal fade" id="enableMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="enableMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title" id="enableMaintenanceModalLabel">
                    <i class="fas fa-pause mr-2"></i>Aktifkan Maintenance Mode
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pengaturan.database.maintenance.aktifkan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <p class="mb-0">
                            Mode maintenance akan membuat aplikasi tidak dapat diakses oleh user biasa.
                            Hanya admin yang dapat mengakses aplikasi.
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="maintenance_message" class="form-label">Pesan Maintenance (Opsional):</label>
                        <textarea class="form-control" id="maintenance_message" name="message" rows="3" placeholder="Aplikasi sedang dalam maintenance. Silakan coba lagi nanti."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="maintenance_admin_password" class="form-label">Password Admin:</label>
                        <input type="password" class="form-control" id="maintenance_admin_password" name="admin_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-pause mr-2"></i>Aktifkan Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disable Maintenance Modal -->
<div class="modal fade" id="disableMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="disableMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title" id="disableMaintenanceModalLabel">
                    <i class="fas fa-play mr-2"></i>Nonaktifkan Maintenance Mode
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pengaturan.database.maintenance.nonaktifkan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <p class="mb-0">
                            Aplikasi akan kembali dapat diakses oleh semua pengguna setelah maintenance mode dinonaktifkan.
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="disable_admin_password" class="form-label">Password Admin:</label>
                        <input type="password" class="form-control" id="disable_admin_password" name="admin_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play mr-2"></i>Nonaktifkan Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto refresh database info every 30 seconds (disabled during maintenance)
    @if(!$maintenanceMode)
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif

    // Multi-step confirmation for database reset
    $('#resetDatabaseModal form').on('submit', function(e) {
        const conf1 = $('#confirmation_1').val();
        const conf2 = $('#confirmation_2').val();
        const conf3 = $('#confirmation_3').val();

        if (conf1 !== 'RESET' || conf2 !== 'DATABASE' || conf3 !== 'CONFIRM') {
            e.preventDefault();
            alert('Konfirmasi tidak valid! Pastikan Anda mengetik RESET, DATABASE, dan CONFIRM dengan benar.');
            return false;
        }

        if (!confirm('PERINGATAN TERAKHIR!\n\nAnda yakin ingin menghapus SEMUA DATA dari database?\n\nOperasi ini TIDAK DAPAT DIBATALKAN!')) {
            e.preventDefault();
            return false;
        }
    });

    // Real-time validation for confirmation inputs
    $('#confirmation_1, #confirmation_2, #confirmation_3').on('input', function() {
        const conf1 = $('#confirmation_1').val();
        const conf2 = $('#confirmation_2').val();
        const conf3 = $('#confirmation_3').val();
        const submitBtn = $('#resetDatabaseModal form button[type="submit"]');

        if (conf1 === 'RESET' && conf2 === 'DATABASE' && conf3 === 'CONFIRM') {
            submitBtn.prop('disabled', false);
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            submitBtn.prop('disabled', true);
            if ($(this).val() !== '') {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        }
    });

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
        } else if (action.includes('maintenance')) {
            // Skip confirmation for maintenance modals (already handled in modal)
            return true;
        } else if (action.includes('reset')) {
            // Skip confirmation for reset (already handled above)
            return true;
        }

        if (message && !confirm(message)) {
            e.preventDefault();
        }
    });

    // Initialize reset button as disabled
    $('#resetDatabaseModal form button[type="submit"]').prop('disabled', true);
});

// Copy bypass URL function
function copyBypassUrl() {
    const urlInput = document.getElementById('bypassUrl');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // For mobile devices

    try {
        document.execCommand('copy');

        // Change button text temporarily
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');

        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);

    } catch (err) {
        alert('Gagal copy URL. Silakan copy manual.');
    }
}

// Copy current secret key function
function copyCurrentSecret() {
    @if($currentSecret ?? false)
    const secretKey = '{{ $currentSecret }}';

    // Create temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = secretKey;
    document.body.appendChild(tempInput);
    tempInput.select();
    tempInput.setSelectionRange(0, 99999);

    try {
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Change button text temporarily
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
        btn.classList.remove('btn-info');
        btn.classList.add('btn-success');

        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-info');
        }, 2000);

    } catch (err) {
        document.body.removeChild(tempInput);
        alert('Gagal copy secret key. Silakan copy manual: ' + secretKey);
    }
    @else
    alert('Secret key tidak tersedia.');
    @endif
}
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
