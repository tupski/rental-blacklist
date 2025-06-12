@extends('layouts.main')

@section('title', 'Masuk ke Akun')

@section('content')
<div class="bg-gradient-to-br from-danger-subtle to-warning-subtle min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt text-white fs-2"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-muted">
                        Akses dashboard pengusaha rental
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Login Form -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
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
                                    autofocus
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

                            <!-- Password -->
                            <div class="mb-3">
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
                                        autocomplete="current-password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Masukkan kata sandi"
                                    >
                                    <button
                                        type="button"
                                        onclick="togglePassword()"
                                        class="btn btn-outline-secondary"
                                    >
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input
                                        id="remember_me"
                                        type="checkbox"
                                        name="remember"
                                        class="form-check-input"
                                    >
                                    <label for="remember_me" class="form-check-label">Ingat saya</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none text-danger">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="btn btn-danger btn-lg w-100 mb-3"
                            >
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Masuk ke Dashboard
                            </button>
                        </form>

                        <!-- Demo Accounts -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-1"></i>
                                Akun Demo untuk Testing
                            </h6>
                            <div class="small">
                                <p class="mb-1"><strong>Admin:</strong> admin@rentalguard.id | <strong>Password:</strong> admin123</p>
                                <p class="mb-1"><strong>Rental:</strong> rental@example.com | <strong>Password:</strong> rental123</p>
                                <p class="mb-0"><strong>User:</strong> user@example.com | <strong>Password:</strong> user123</p>
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-decoration-none text-danger fw-medium">
                                    Daftar sekarang
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
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

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
