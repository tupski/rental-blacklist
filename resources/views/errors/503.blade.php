@extends('layouts.main')

@section('title', 'Maintenance Mode')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- Error Illustration -->
            <div class="mb-5">
                <div class="error-illustration">
                    <i class="fas fa-tools text-warning" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="error-content">
                <h1 class="display-1 fw-bold text-warning mb-3">503</h1>
                <h2 class="h3 text-dark mb-4">Sedang Maintenance</h2>
                <p class="lead text-muted mb-5">
                    Sistem sedang dalam tahap pemeliharaan untuk meningkatkan kualitas layanan. 
                    Mohon tunggu beberapa saat dan coba lagi nanti.
                </p>

                <!-- Maintenance Info -->
                <div class="mt-5">
                    <div class="alert alert-warning border">
                        <h6 class="alert-heading">
                            <i class="fas fa-wrench text-warning me-2"></i>
                            Informasi Maintenance
                        </h6>
                        <p class="mb-2">
                            <strong>Status:</strong> Sedang dalam pemeliharaan
                        </p>
                        <p class="mb-2">
                            <strong>Estimasi selesai:</strong> Beberapa menit lagi
                        </p>
                        <p class="mb-0">
                            <strong>Jenis:</strong> Pembaruan sistem dan database
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mt-4">
                    <button onclick="location.reload()" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo me-2"></i>
                        Coba Lagi
                    </button>
                    <button onclick="checkStatus()" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-heartbeat me-2"></i>
                        Cek Status
                    </button>
                </div>

                <!-- Progress Indicator -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Progress Maintenance</h6>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" 
                             role="progressbar" style="width: 75%">
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Hampir selesai...</small>
                </div>

                <!-- What's Being Updated -->
                <div class="mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Yang Sedang Diperbaiki
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-md-6">
                                    <h6 class="text-success">
                                        <i class="fas fa-check me-1"></i>
                                        Selesai
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Update keamanan sistem</li>
                                        <li>Optimasi database</li>
                                        <li>Backup data</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-warning">
                                        <i class="fas fa-spinner fa-spin me-1"></i>
                                        Sedang Proses
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Update fitur pencarian</li>
                                        <li>Perbaikan bug minor</li>
                                        <li>Testing sistem</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Butuh bantuan darurat?</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="mailto:admin@rentalblacklist.com" class="text-decoration-none">
                            <i class="fas fa-envelope text-info me-1"></i>
                            Email Admin
                        </a>
                        <a href="https://wa.me/6281911919993" class="text-decoration-none" target="_blank">
                            <i class="fab fa-whatsapp text-success me-1"></i>
                            WhatsApp
                        </a>
                        <a href="tel:+6281911919993" class="text-decoration-none">
                            <i class="fas fa-phone text-primary me-1"></i>
                            Telepon
                        </a>
                    </div>
                </div>

                <!-- Auto Refresh Notice -->
                <div class="mt-5">
                    <div class="alert alert-light border">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        <small>
                            Halaman ini akan otomatis refresh setiap 30 detik untuk mengecek status sistem.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-illustration {
    animation: rotate 3s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.error-content {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Auto refresh every 30 seconds
setTimeout(function() {
    location.reload();
}, 30000);

// Check status function
function checkStatus() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengecek...';
    btn.disabled = true;
    
    setTimeout(function() {
        location.reload();
    }, 2000);
}
</script>
@endsection
