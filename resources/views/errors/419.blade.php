@extends('layouts.main')

@section('title', 'Sesi Berakhir')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- Error Illustration -->
            <div class="mb-5">
                <div class="error-illustration">
                    <i class="fas fa-clock text-info" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="error-content">
                <h1 class="display-1 fw-bold text-info mb-3">419</h1>
                <h2 class="h3 text-dark mb-4">Sesi Berakhir</h2>
                <p class="lead text-muted mb-5">
                    Sesi Anda telah berakhir karena tidak ada aktivitas dalam waktu yang lama. 
                    Silakan refresh halaman atau kembali ke beranda untuk melanjutkan.
                </p>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <button onclick="location.reload()" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo me-2"></i>
                        Refresh Halaman
                    </button>
                    <a href="{{ route('beranda') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Information -->
                <div class="mt-5">
                    <div class="alert alert-info border">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Mengapa ini terjadi?
                        </h6>
                        <p class="mb-2">
                            Sesi berakhir untuk menjaga keamanan data Anda. Hal ini terjadi ketika:
                        </p>
                        <ul class="text-start mb-0">
                            <li>Tidak ada aktivitas dalam waktu lama</li>
                            <li>Token keamanan telah berakhir</li>
                            <li>Browser atau tab telah ditutup terlalu lama</li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Aksi Cepat:</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        @auth
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-tachometer-alt text-primary me-1"></i>
                            Dashboard
                        </a>
                        @else
                        <a href="{{ route('masuk') }}" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt text-primary me-1"></i>
                            Login
                        </a>
                        @endauth
                        <a href="{{ route('beranda') }}" class="text-decoration-none">
                            <i class="fas fa-search text-success me-1"></i>
                            Cari Blacklist
                        </a>
                        <a href="{{ route('laporan.buat') }}" class="text-decoration-none">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            Buat Laporan
                        </a>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>
                                Tips Keamanan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-md-6">
                                    <h6 class="text-success">
                                        <i class="fas fa-check me-1"></i>
                                        Yang Harus Dilakukan
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Selalu logout setelah selesai</li>
                                        <li>Gunakan password yang kuat</li>
                                        <li>Jangan tinggalkan browser terbuka</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-danger">
                                        <i class="fas fa-times me-1"></i>
                                        Yang Harus Dihindari
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Login di komputer umum</li>
                                        <li>Berbagi akun dengan orang lain</li>
                                        <li>Mengabaikan peringatan keamanan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-illustration {
    animation: tick 2s ease-in-out infinite;
}

@keyframes tick {
    0%, 100% {
        transform: rotate(0deg);
    }
    25% {
        transform: rotate(-10deg);
    }
    75% {
        transform: rotate(10deg);
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
@endsection
