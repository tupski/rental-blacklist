@extends('layouts.main')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- Error Illustration -->
            <div class="mb-5">
                <div class="error-illustration">
                    <i class="fas fa-search text-danger" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="error-content">
                <h1 class="display-1 fw-bold text-danger mb-3">404</h1>
                <h2 class="h3 text-dark mb-4">Halaman Tidak Ditemukan</h2>
                <p class="lead text-muted mb-5">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan.
                    Mungkin halaman telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.
                </p>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Halaman Sebelumnya
                    </button>
                </div>

                <!-- Search Suggestion -->
                <div class="mt-5">
                    <h5 class="text-dark mb-3">Atau coba cari blacklist:</h5>
                    <form action="{{ route('beranda') }}" method="GET" class="d-flex justify-content-center">
                        <div class="input-group" style="max-width: 400px;">
                            <input type="text" name="cari" class="form-control form-control-lg"
                                   placeholder="Masukkan NIK, nama, atau nomor HP">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Help Links -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Butuh bantuan?</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('laporan.buat') }}" class="text-decoration-none">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            Laporkan Masalah
                        </a>
                        <a href="{{ route('kontak') }}" class="text-decoration-none">
                            <i class="fas fa-envelope text-info me-1"></i>
                            Hubungi Kami
                        </a>
                        <a href="{{ route('daftar') }}" class="text-decoration-none">
                            <i class="fas fa-store text-success me-1"></i>
                            Daftar Rental
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-illustration {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
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
