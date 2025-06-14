@extends('layouts.main')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- Error Illustration -->
            <div class="mb-5">
                <div class="error-illustration">
                    <i class="fas fa-lock text-warning" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="error-content">
                <h1 class="display-1 fw-bold text-warning mb-3">403</h1>
                <h2 class="h3 text-dark mb-4">Akses Ditolak</h2>
                <p class="lead text-muted mb-5">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                    Silakan login dengan akun yang memiliki hak akses yang sesuai.
                </p>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    @guest
                    <a href="{{ route('masuk') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </a>
                    <a href="{{ route('daftar') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Akun
                    </a>
                    @else
                    <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Halaman Sebelumnya
                    </button>
                    @endguest
                </div>

                <!-- User Information -->
                @auth
                <div class="mt-5">
                    <div class="alert alert-info border">
                        <h6 class="alert-heading">
                            <i class="fas fa-user text-info me-2"></i>
                            Informasi Akun
                        </h6>
                        <p class="mb-2">
                            <strong>Login sebagai:</strong> {{ auth()->user()->name }}
                        </p>
                        <p class="mb-0">
                            <strong>Role:</strong> 
                            @if(auth()->user()->role === 'admin')
                                <span class="badge bg-danger">Administrator</span>
                            @elseif(auth()->user()->role === 'rental_owner')
                                <span class="badge bg-success">Pemilik Rental</span>
                            @else
                                <span class="badge bg-primary">User</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endauth

                <!-- Help Information -->
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Butuh bantuan?</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        @guest
                        <a href="{{ route('masuk') }}" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt text-primary me-1"></i>
                            Login ke Akun
                        </a>
                        @endguest
                        <a href="{{ route('kontak') }}" class="text-decoration-none">
                            <i class="fas fa-envelope text-info me-1"></i>
                            Hubungi Admin
                        </a>
                        <a href="{{ route('beranda') }}" class="text-decoration-none">
                            <i class="fas fa-search text-success me-1"></i>
                            Cari Blacklist
                        </a>
                    </div>
                </div>

                <!-- Access Levels Information -->
                <div class="mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Level Akses Sistem
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-md-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-user me-1"></i>
                                        User Biasa
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Cari blacklist</li>
                                        <li>Buat laporan</li>
                                        <li>Lihat dashboard</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-success">
                                        <i class="fas fa-store me-1"></i>
                                        Pemilik Rental
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Semua akses user</li>
                                        <li>Data tidak disensor</li>
                                        <li>Profil publik</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-danger">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Administrator
                                    </h6>
                                    <ul class="small text-muted">
                                        <li>Kelola semua data</li>
                                        <li>Validasi laporan</li>
                                        <li>Pengaturan sistem</li>
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
    animation: shake 2s ease-in-out infinite;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
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
