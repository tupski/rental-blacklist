@extends('layouts.main')

@section('title', 'Daftar')

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
                        Pilih jenis akun yang sesuai dengan kebutuhan Anda
                    </p>
                </div>

                <!-- Registration Header -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-car fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-primary mb-2">Registrasi Pemilik Rental</h4>
                        <p class="text-muted mb-0">Daftar sebagai pengusaha rental dan dapatkan akses penuh ke sistem blacklist</p>
                    </div>
                </div>

                <!-- Register Form -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('daftar') }}" id="registrationForm">
                            @csrf
                            <input type="hidden" name="user_type" id="user_type" value="rental">

                            <!-- Rental Owner Form -->
                            <div id="rental_form" class="registration-form">

                                <!-- Detail Penanggung Jawab -->
                                <h6 class="text-secondary mb-3">Detail Penanggung Jawab</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="responsible_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('responsible_name') is-invalid @enderror"
                                               id="responsible_name" name="responsible_name" value="{{ old('responsible_name') }}" required>
                                        @error('responsible_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="responsible_position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('responsible_position') is-invalid @enderror"
                                                id="responsible_position" name="responsible_position" required>
                                            <option value="">Pilih Jabatan</option>
                                            <option value="Pemilik" {{ old('responsible_position') == 'Pemilik' ? 'selected' : '' }}>Pemilik</option>
                                            <option value="Direktur" {{ old('responsible_position') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                            <option value="Manager" {{ old('responsible_position') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="Admin" {{ old('responsible_position') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="Staff" {{ old('responsible_position') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                        </select>
                                        @error('responsible_position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="responsible_phone" class="form-label">No. HP <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('responsible_phone') is-invalid @enderror"
                                               id="responsible_phone" name="responsible_phone" value="{{ old('responsible_phone') }}" required>
                                        @error('responsible_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="responsible_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('responsible_email') is-invalid @enderror"
                                               id="responsible_email" name="responsible_email" value="{{ old('responsible_email') }}" required>
                                        @error('responsible_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Detail Perusahaan Rental -->
                                <h6 class="text-secondary mb-3 mt-4">Detail Perusahaan Rental</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="entity_type" class="form-label">Badan Hukum <span class="text-danger">*</span></label>
                                        <select class="form-select @error('entity_type') is-invalid @enderror"
                                                id="entity_type" name="entity_type" required>
                                            <option value="">Pilih Badan Hukum</option>
                                            <option value="PT" {{ old('entity_type') == 'PT' ? 'selected' : '' }}>PT (Perseroan Terbatas)</option>
                                            <option value="CV" {{ old('entity_type') == 'CV' ? 'selected' : '' }}>CV (Commanditaire Vennootschap)</option>
                                            <option value="UD" {{ old('entity_type') == 'UD' ? 'selected' : '' }}>UD (Usaha Dagang)</option>
                                            <option value="Perorangan" {{ old('entity_type') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                                        </select>
                                        @error('entity_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                               id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_phone" class="form-label">No. HP Perusahaan</label>
                                        <input type="tel" class="form-control @error('company_phone') is-invalid @enderror"
                                               id="company_phone" name="company_phone" value="{{ old('company_phone') }}">
                                        @error('company_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="company_email" class="form-label">Email Perusahaan</label>
                                        <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                               id="company_email" name="company_email" value="{{ old('company_email') }}">
                                        @error('company_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror"
                                              id="company_address" name="company_address" rows="3" required>{{ old('company_address') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="legal_document" class="form-label">Bukti Legalitas (Opsional)</label>
                                    <input type="file" class="form-control @error('legal_document') is-invalid @enderror"
                                           id="legal_document" name="legal_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Format: PDF, JPG, PNG. Maksimal 2MB</small>
                                    @error('legal_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <!-- Password Section -->
                            <div class="password-section mt-4">
                                <h6 class="text-secondary mb-3">Kata Sandi</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password" required>
                                            <button type="button" onclick="togglePassword('password')" class="btn btn-outline-secondary">
                                                <i class="fas fa-eye" id="toggleIcon1"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">Minimal 8 karakter, kombinasi huruf dan angka</small>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   id="password_confirmation" name="password_confirmation" required>
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="btn btn-outline-secondary">
                                                <i class="fas fa-eye" id="toggleIcon2"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="form-check mb-4 mt-4">
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

                            <!-- Captcha -->
                            <x-captcha form-type="register" />

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg w-100 mb-4"
                            >
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </button>
                        </form>

                        <!-- Benefits -->
                        <div class="alert alert-success mt-4">
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
                                <a href="{{ route('masuk') }}" class="text-decoration-none text-primary fw-medium">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center">
                            <a href="{{ route('beranda') }}" class="text-decoration-none text-muted">
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
$(document).ready(function() {
    // Form is ready for rental registration only
    console.log('Rental registration form loaded');
});

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
