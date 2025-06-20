@extends('layouts.main')

@section('title', 'API Documentation')

@section('content')
<div class="bg-gradient-info py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-dark mb-3">
                        <i class="fas fa-code text-info me-3"></i>
                        API Documentation
                    </h1>
                    <p class="lead text-muted">
                        Integrasikan sistem blacklist rental ke aplikasi Anda dengan mudah
                    </p>
                </div>

                <!-- API Key Management -->
                @auth
                @php
                    $canUseApi = Auth::user()->canUseApi();
                    $requiresEmailVerification = Auth::user()->requiresEmailVerification();
                    $isActive = Auth::user()->isActive();
                @endphp
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-key me-2"></i>
                            API Key Management
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(!$canUseApi)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Akses API Terbatas</strong><br>
                                @if(!$isActive)
                                    Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan API.
                                @elseif($requiresEmailVerification)
                                    Email belum diverifikasi. Silakan <a href="{{ route('verifikasi.pemberitahuan') }}" class="alert-link">verifikasi email</a> terlebih dahulu untuk dapat menggunakan API.
                                @endif
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="apiKeyField" class="form-label fw-bold">Your API Key</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="apiKeyField"
                                           placeholder="Klik 'Generate' untuk membuat API key" readonly
                                           @if(!$canUseApi) disabled @endif>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleApiKey"
                                            @if(!$canUseApi) disabled @endif>
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                    <button class="btn btn-primary" type="button" id="copyApiKey"
                                            @if(!$canUseApi) disabled @endif>
                                        <i class="fas fa-copy me-1"></i>
                                        Copy
                                    </button>
                                </div>
                                <small class="text-muted">API key ini digunakan untuk mengakses endpoint yang memerlukan authentication</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Actions</label>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" id="generateApiKey"
                                            @if(!$canUseApi) disabled @endif>
                                        <i class="fas fa-plus me-1"></i>
                                        Generate Key
                                    </button>
                                    <button class="btn btn-warning" id="resetApiKey"
                                            @if(!$canUseApi) disabled @endif>
                                        <i class="fas fa-refresh me-1"></i>
                                        Reset Key
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="apiKeyInfo" class="mt-3 d-none">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Last Used:</strong> <span id="lastUsed">Never</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- API Info Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi API
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">Base URL</h6>
                                <code class="bg-light p-2 rounded d-block">{{ url('/api/v1') }}</code>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Format Response</h6>
                                <code class="bg-light p-2 rounded d-block">JSON</code>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Authentication</h6>
                                <span class="badge bg-warning">Bearer Token (untuk endpoint tertentu)</span>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Rate Limit</h6>
                                <span class="badge bg-success">100 requests/minute</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Public Endpoints -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-unlock me-2"></i>
                            Public Endpoints (No Authentication)
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Search Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-success">
                                <span class="badge bg-primary me-2">GET</span>
                                /search
                            </h5>
                            <p class="text-muted">Mencari data blacklist berdasarkan NIK atau nama</p>

                            <h6 class="mt-3">Parameters:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>q</code></td>
                                            <td>string</td>
                                            <td><span class="badge bg-danger">Yes</span></td>
                                            <td>Query pencarian (min. 3 karakter)</td>
                                        </tr>
                                        <tr>
                                            <td><code>limit</code></td>
                                            <td>integer</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>Jumlah hasil (default: 10, max: 100)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="mt-3">Example Request:</h6>
                            <pre class="bg-light p-3 rounded"><code>GET {{ url('/api/v1/search') }}?q=john&limit=5</code></pre>

                            <h6 class="mt-3">Example Response:</h6>
                            <pre class="bg-light p-3 rounded"><code>{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama_lengkap": "J**n D*e",
      "nik": "1234****5678",
      "no_hp": "0812****89",
      "jenis_rental": "Motor",
      "jenis_laporan": ["Tidak Mengembalikan"],
      "tanggal_kejadian": "2024-01-15",
      "jumlah_laporan": 2,
      "pelapor": "Rental ABC",
      "created_at": "2024-01-16 10:30:00"
    }
  ],
  "total": 1,
  "query": "john"
}</code></pre>
                        </div>

                        <!-- Detail Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-success">
                                <span class="badge bg-primary me-2">GET</span>
                                /blacklist/{id}
                            </h5>
                            <p class="text-muted">Mendapatkan detail data blacklist (data tersensor)</p>

                            <h6 class="mt-3">Example Request:</h6>
                            <pre class="bg-light p-3 rounded"><code>GET {{ url('/api/v1/blacklist/1') }}</code></pre>

                            <h6 class="mt-3">Example Response:</h6>
                            <pre class="bg-light p-3 rounded"><code>{
  "success": true,
  "message": "For full access, please register as rental business (FREE) or purchase credits.",
  "data": {
    "id": 1,
    "nama_lengkap": "J**n D*e",
    "nik": "1234****5678",
    "no_hp": "0812****89",
    "jenis_rental": "Motor",
    "jenis_laporan": ["Tidak Mengembalikan"],
    "tanggal_kejadian": "2024-01-15",
    "jumlah_laporan": 2,
    "pelapor": "Rental ABC",
    "created_at": "2024-01-16 10:30:00"
  }
}</code></pre>
                        </div>

                        <!-- Stats Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-success">
                                <span class="badge bg-primary me-2">GET</span>
                                /stats
                            </h5>
                            <p class="text-muted">Mendapatkan statistik database blacklist</p>

                            <h6 class="mt-3">Example Request:</h6>
                            <pre class="bg-light p-3 rounded"><code>GET {{ url('/api/v1/stats') }}</code></pre>

                            <h6 class="mt-3">Example Response:</h6>
                            <pre class="bg-light p-3 rounded"><code>{
  "success": true,
  "data": {
    "total_laporan": 1250,
    "total_pelanggan_bermasalah": 890,
    "rental_terdaftar": 45,
    "laporan_bulan_ini": 23
  }
}</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Authenticated Endpoints -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-lock me-2"></i>
                            Authenticated Endpoints (Bearer Token Required)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Authentication:</strong> Untuk menggunakan endpoint ini, Anda perlu mendaftar sebagai rental dan mendapatkan API token.
                            Tambahkan header: <code>Authorization: Bearer YOUR_TOKEN</code>
                        </div>

                        <!-- List All Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-warning">
                                <span class="badge bg-primary me-2">GET</span>
                                /blacklist
                            </h5>
                            <p class="text-muted">Mendapatkan semua data blacklist dengan akses penuh (tanpa sensor)</p>

                            <h6 class="mt-3">Parameters:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>page</code></td>
                                            <td>integer</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>Halaman (default: 1)</td>
                                        </tr>
                                        <tr>
                                            <td><code>limit</code></td>
                                            <td>integer</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>Jumlah per halaman (default: 15, max: 100)</td>
                                        </tr>
                                        <tr>
                                            <td><code>search</code></td>
                                            <td>string</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>Filter pencarian</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Create Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-warning">
                                <span class="badge bg-success me-2">POST</span>
                                /blacklist
                            </h5>
                            <p class="text-muted">Menambahkan data blacklist baru</p>

                            <h6 class="mt-3">Request Body:</h6>
                            <pre class="bg-light p-3 rounded"><code>{
  "nik": "1234567890123456",
  "nama_lengkap": "John Doe",
  "jenis_kelamin": "Laki-laki",
  "no_hp": "081234567890",
  "alamat": "Jl. Contoh No. 123",
  "jenis_rental": "Motor",
  "jenis_laporan": ["Tidak Mengembalikan"],
  "kronologi": "Pelanggan tidak mengembalikan motor...",
  "tanggal_kejadian": "2024-01-15"
}</code></pre>
                        </div>

                        <!-- Update Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-warning">
                                <span class="badge bg-info me-2">PUT</span>
                                /blacklist/{id}
                            </h5>
                            <p class="text-muted">Mengupdate data blacklist (hanya data milik sendiri)</p>
                        </div>

                        <!-- Delete Endpoint -->
                        <div class="mb-4">
                            <h5 class="text-warning">
                                <span class="badge bg-danger me-2">DELETE</span>
                                /blacklist/{id}
                            </h5>
                            <p class="text-muted">Menghapus data blacklist (hanya data milik sendiri)</p>
                        </div>
                    </div>
                </div>

                <!-- Error Codes -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-danger text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error Codes
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Message</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>400</code></td>
                                        <td>Bad Request</td>
                                        <td>Parameter tidak valid atau kurang</td>
                                    </tr>
                                    <tr>
                                        <td><code>401</code></td>
                                        <td>Unauthorized</td>
                                        <td>Token tidak valid atau tidak ada</td>
                                    </tr>
                                    <tr>
                                        <td><code>403</code></td>
                                        <td>Forbidden</td>
                                        <td>Tidak memiliki akses ke resource</td>
                                    </tr>
                                    <tr>
                                        <td><code>404</code></td>
                                        <td>Not Found</td>
                                        <td>Data tidak ditemukan</td>
                                    </tr>
                                    <tr>
                                        <td><code>429</code></td>
                                        <td>Too Many Requests</td>
                                        <td>Rate limit terlampaui</td>
                                    </tr>
                                    <tr>
                                        <td><code>500</code></td>
                                        <td>Internal Server Error</td>
                                        <td>Kesalahan server</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Getting Started -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-rocket me-2"></i>
                            Getting Started
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">1. Daftar Rental</h6>
                                <p class="text-muted mb-3">Daftarkan bisnis rental Anda untuk mendapatkan akses penuh</p>
                                <a href="{{ route('rental.daftar') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Daftar Sekarang
                                </a>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">2. Dapatkan API Token</h6>
                                <p class="text-muted mb-3">Setelah verifikasi, dapatkan API token di dashboard</p>
                                @auth
                                    <a href="{{ route('dasbor') }}" class="btn btn-success">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        Ke Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('masuk') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@auth
@push('scripts')
<script>
$(document).ready(function() {
    let currentApiKey = '';
    let isVisible = false;

    // Load existing API key
    loadApiKey();

    // Toggle API key visibility
    $('#toggleApiKey').on('click', function() {
        if (isVisible) {
            $('#apiKeyField').attr('type', 'password');
            $('#toggleIcon').removeClass('fa-eye-slash').addClass('fa-eye');
            isVisible = false;
        } else {
            $('#apiKeyField').attr('type', 'text');
            $('#toggleIcon').removeClass('fa-eye').addClass('fa-eye-slash');
            isVisible = true;
        }
    });

    // Copy API key
    $('#copyApiKey').on('click', function() {
        if (currentApiKey) {
            navigator.clipboard.writeText(currentApiKey).then(function() {
                showAlert('API key berhasil disalin!', 'success');
            }).catch(function() {
                // Fallback for older browsers
                $('#apiKeyField').attr('type', 'text').select();
                document.execCommand('copy');
                $('#apiKeyField').attr('type', 'password');
                showAlert('API key berhasil disalin!', 'success');
            });
        } else {
            showAlert('Tidak ada API key untuk disalin', 'warning');
        }
    });

    // Generate API key
    $('#generateApiKey').on('click', function() {
        if (currentApiKey && !confirm('Ini akan mengganti API key yang ada. Lanjutkan?')) {
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Generating...');

        $.ajax({
            url: '{{ route('kunci-api.buat') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    currentApiKey = response.api_key;
                    $('#apiKeyField').val(currentApiKey);
                    $('#apiKeyInfo').removeClass('d-none');
                    $('#lastUsed').text('Never');
                    showAlert(response.message, 'success');
                }
            },
            error: function(xhr) {
                console.error('Generate error:', xhr);
                let message = 'Terjadi kesalahan saat membuat API key';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert(message, 'danger');
            },
            complete: function() {
                $('#generateApiKey').prop('disabled', false).html('<i class="fas fa-plus me-1"></i>Generate Key');
            }
        });
    });

    // Reset API key
    $('#resetApiKey').on('click', function() {
        if (!confirm('Ini akan mereset API key Anda. API key lama tidak akan bisa digunakan lagi. Lanjutkan?')) {
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Resetting...');

        $.ajax({
            url: '{{ route('kunci-api.reset') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    currentApiKey = response.api_key;
                    $('#apiKeyField').val(currentApiKey);
                    $('#apiKeyInfo').removeClass('d-none');
                    $('#lastUsed').text('Never');
                    showAlert(response.message, 'success');
                }
            },
            error: function(xhr) {
                console.error('Reset error:', xhr);
                let message = 'Terjadi kesalahan saat mereset API key';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert(message, 'danger');
            },
            complete: function() {
                $('#resetApiKey').prop('disabled', false).html('<i class="fas fa-refresh me-1"></i>Reset Key');
            }
        });
    });

    function loadApiKey() {
        $.ajax({
            url: '{{ route('kunci-api.tampil') }}',
            method: 'GET',
            success: function(response) {
                if (response.success && response.api_key) {
                    currentApiKey = response.api_key;
                    $('#apiKeyField').val(currentApiKey);
                    $('#apiKeyInfo').removeClass('d-none');

                    if (response.last_used) {
                        const lastUsed = new Date(response.last_used).toLocaleString('id-ID');
                        $('#lastUsed').text(lastUsed);
                    } else {
                        $('#lastUsed').text('Never');
                    }
                }
            },
            error: function(xhr) {
                console.error('Load API key error:', xhr);
            }
        });
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Remove existing alerts
        $('.alert').remove();

        // Add new alert at the top of the API key card
        $('.card-body').first().prepend(alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endpush
@endauth
