@extends('layouts.main')

@section('title', 'Kesalahan Server')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- Error Illustration -->
            <div class="mb-5">
                <div class="error-illustration">
                    <i class="fas fa-server text-danger" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="error-content">
                <h1 class="display-1 fw-bold text-danger mb-3">500</h1>
                <h2 class="h3 text-dark mb-4">Kesalahan Server Internal</h2>
                <p class="lead text-muted mb-5">
                    Maaf, terjadi kesalahan pada server kami. 
                    Tim teknis kami telah diberitahu dan sedang bekerja untuk memperbaiki masalah ini.
                </p>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-redo me-2"></i>
                        Coba Lagi
                    </button>
                </div>

                <!-- Status Information -->
                <div class="mt-5">
                    <div class="alert alert-light border">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Informasi
                        </h6>
                        <p class="mb-2">
                            <strong>Waktu:</strong> {{ now()->format('d/m/Y H:i:s') }}
                        </p>
                        <p class="mb-0">
                            <strong>Kode Error:</strong> HTTP 500 - Internal Server Error
                        </p>
                    </div>
                </div>

                <!-- Alternative Actions -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Sementara itu, Anda dapat:</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('beranda') }}" class="text-decoration-none">
                            <i class="fas fa-search text-primary me-1"></i>
                            Cari Blacklist
                        </a>
                        <a href="{{ route('laporan.buat') }}" class="text-decoration-none">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            Buat Laporan
                        </a>
                        <a href="{{ route('kontak') }}" class="text-decoration-none">
                            <i class="fas fa-envelope text-info me-1"></i>
                            Hubungi Support
                        </a>
                    </div>
                </div>

                <!-- Technical Details (for development) -->
                @if(config('app.debug'))
                <div class="mt-5">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-bug text-warning me-2"></i>
                            Debug Information
                        </h6>
                        <p class="mb-0">
                            Mode debug aktif. Periksa log aplikasi untuk detail error.
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.error-illustration {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.5;
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
