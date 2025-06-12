@extends('layouts.main')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="bg-gradient-to-br from-primary-subtle to-info-subtle min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus text-white fs-2"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">
                        Daftar Akun Baru
                    </h2>
                    <p class="text-muted">
                        Bergabung sebagai pengusaha rental
                    </p>
                </div>

                <!-- Register Form -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-medium">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        Nama Lengkap
                                    </label>
                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        placeholder="Masukkan nama lengkap"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        Alamat Email
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="masukkan@email.com"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="fas fa-lock me-2 text-muted"></i>
                                        Kata Sandi
                                    </label>
                                    <div class="input-group">
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter"
                                        >
                                        <button
                                            type="button"
                                            onclick="togglePassword('password')"
                                            class="btn btn-outline-secondary"
                                        >
                                            <i class="fas fa-eye" id="toggleIcon1"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="fas fa-lock me-2 text-muted"></i>
                                        Konfirmasi Kata Sandi
                                    </label>
                                    <div class="input-group">
                                        <input
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                            placeholder="Ulangi kata sandi"
                                        >
                                        <button
                                            type="button"
                                            onclick="togglePassword('password_confirmation')"
                                            class="btn btn-outline-secondary"
                                        >
                                            <i class="fas fa-eye" id="toggleIcon2"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="form-check mb-4">
                                <input
                                    id="terms"
                                    type="checkbox"
                                    required
                                    class="form-check-input"
                                >
                                <label for="terms" class="form-check-label">
                                    Saya setuju dengan
                                    <a href="#" class="text-decoration-none text-primary">syarat dan ketentuan</a>
                                    serta
                                    <a href="#" class="text-decoration-none text-primary">kebijakan privasi</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg w-100 mb-3"
                            >
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </button>
                        </form>

                        <!-- Benefits -->
                        <div class="alert alert-success">
                            <h6 class="alert-heading">
                                <i class="fas fa-check-circle me-1"></i>
                                Keuntungan Bergabung
                            </h6>
                            <ul class="mb-0 small">
                                <li>Akses data blacklist tanpa sensor</li>
                                <li>Tambah dan kelola laporan</li>
                                <li>100% GRATIS untuk pengusaha rental</li>
                                <li>Lindungi bisnis dari pelanggan bermasalah</li>
                            </ul>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-medium">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali ke beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection
