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
                        Pilih jenis akun yang sesuai dengan kebutuhan Anda
                    </p>
                </div>

                <!-- Registration Type Switch -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="registration_type" id="rental_owner" value="rental" checked>
                                <label class="btn btn-outline-primary w-100 py-3" for="rental_owner">
                                    <i class="fas fa-car fa-2x d-block mb-2"></i>
                                    <strong>Pemilik Rental</strong>
                                    <small class="d-block text-muted">Untuk pengusaha rental</small>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="registration_type" id="general_user" value="user">
                                <label class="btn btn-outline-success w-100 py-3" for="general_user">
                                    <i class="fas fa-user fa-2x d-block mb-2"></i>
                                    <strong>User Umum</strong>
                                    <small class="d-block text-muted">Untuk pengguna biasa</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Register Form -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}" id="registrationForm">
                            @csrf
                            <input type="hidden" name="user_type" id="user_type" value="rental">

                            <!-- Rental Owner Form -->
                            <div id="rental_form" class="registration-form">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-car me-2"></i>Registrasi Pemilik Rental
                                </h5>

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

                            <!-- General User Form -->
                            <div id="user_form" class="registration-form" style="display: none;">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-user me-2"></i>Registrasi User Umum
                                </h5>

                                <!-- Data Pribadi -->
                                <h6 class="text-secondary mb-3">Data Pribadi</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                               id="user_name" name="user_name" value="{{ old('user_name') }}">
                                        @error('user_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="user_nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('user_nik') is-invalid @enderror"
                                               id="user_nik" name="user_nik" value="{{ old('user_nik') }}" maxlength="16">
                                        @error('user_nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('user_birth_date') is-invalid @enderror"
                                               id="user_birth_date" name="user_birth_date" value="{{ old('user_birth_date') }}">
                                        @error('user_birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="user_phone" class="form-label">No. HP/WhatsApp <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('user_phone') is-invalid @enderror"
                                               id="user_phone" name="user_phone" value="{{ old('user_phone') }}">
                                        @error('user_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                                <!-- Domisili (untuk user umum) -->
                                <h6 class="text-secondary mb-3 mt-4">Domisili</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="province" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('province') is-invalid @enderror"
                                                id="province" name="province">
                                            <option value="">Pilih Provinsi</option>
                                            <!-- Provinsi akan diload via JavaScript -->
                                        </select>
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="regency" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                                        <select class="form-select @error('regency') is-invalid @enderror"
                                                id="regency" name="regency">
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                        @error('regency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="district" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('district') is-invalid @enderror"
                                                id="district" name="district">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="village" class="form-label">Kelurahan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('village') is-invalid @enderror"
                                                id="village" name="village">
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                        @error('village')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="user_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('user_address') is-invalid @enderror"
                                              id="user_address" name="user_address" rows="3">{{ old('user_address') }}</textarea>
                                    @error('user_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Login Detail (untuk user umum) -->
                                <h6 class="text-secondary mb-3 mt-4">Detail Login</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('user_email') is-invalid @enderror"
                                               id="user_email" name="user_email" value="{{ old('user_email') }}">
                                        @error('user_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="identity_file" class="form-label">Upload Identitas <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('identity_file') is-invalid @enderror"
                                               id="identity_file" name="identity_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="form-text text-muted">KTP/SIM/Paspor. Format: PDF, JPG, PNG. Maks 2MB</small>
                                        @error('identity_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Section (untuk semua jenis user) -->
                            <div class="password-section">
                                <h6 class="text-secondary mb-3 mt-4">Kata Sandi</h6>
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
$(document).ready(function() {
    // Handle registration type switch
    $('input[name="registration_type"]').on('change', function() {
        const selectedType = $(this).val();
        $('#user_type').val(selectedType);

        if (selectedType === 'rental') {
            $('#rental_form').show();
            $('#user_form').hide();
            // Enable rental form fields
            $('#rental_form input, #rental_form select, #rental_form textarea').prop('required', function() {
                return $(this).data('required') !== false;
            });
            // Disable user form fields
            $('#user_form input, #user_form select, #user_form textarea').prop('required', false);
        } else {
            $('#rental_form').hide();
            $('#user_form').show();
            // Enable user form fields
            $('#user_form input, #user_form select, #user_form textarea').prop('required', function() {
                return $(this).data('required') !== false;
            });
            // Disable rental form fields
            $('#rental_form input, #rental_form select, #rental_form textarea').prop('required', false);
        }
    });

    // Load provinces on page load
    loadProvinces();

    // Handle province change
    $('#province').on('change', function() {
        const provinceId = $(this).val();
        if (provinceId) {
            loadRegencies(provinceId);
        } else {
            $('#regency, #district, #village').html('<option value="">Pilih...</option>');
        }
    });

    // Handle regency change
    $('#regency').on('change', function() {
        const regencyId = $(this).val();
        if (regencyId) {
            loadDistricts(regencyId);
        } else {
            $('#district, #village').html('<option value="">Pilih...</option>');
        }
    });

    // Handle district change
    $('#district').on('change', function() {
        const districtId = $(this).val();
        if (districtId) {
            loadVillages(districtId);
        } else {
            $('#village').html('<option value="">Pilih...</option>');
        }
    });
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

// Indonesia Region API functions
function loadProvinces() {
    $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function(data) {
        let options = '<option value="">Pilih Provinsi</option>';
        data.forEach(function(province) {
            options += `<option value="${province.id}">${province.name}</option>`;
        });
        $('#province').html(options);
    }).fail(function() {
        $('#province').html('<option value="">Error loading provinces</option>');
    });
}

function loadRegencies(provinceId) {
    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`, function(data) {
        let options = '<option value="">Pilih Kabupaten/Kota</option>';
        data.forEach(function(regency) {
            options += `<option value="${regency.id}">${regency.name}</option>`;
        });
        $('#regency').html(options);
        $('#district, #village').html('<option value="">Pilih...</option>');
    }).fail(function() {
        $('#regency').html('<option value="">Error loading regencies</option>');
    });
}

function loadDistricts(regencyId) {
    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`, function(data) {
        let options = '<option value="">Pilih Kecamatan</option>';
        data.forEach(function(district) {
            options += `<option value="${district.id}">${district.name}</option>`;
        });
        $('#district').html(options);
        $('#village').html('<option value="">Pilih...</option>');
    }).fail(function() {
        $('#district').html('<option value="">Error loading districts</option>');
    });
}

function loadVillages(districtId) {
    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`, function(data) {
        let options = '<option value="">Pilih Kelurahan</option>';
        data.forEach(function(village) {
            options += `<option value="${village.id}">${village.name}</option>`;
        });
        $('#village').html(options);
    }).fail(function() {
        $('#village').html('<option value="">Error loading villages</option>');
    });
}
</script>
@endpush
@endsection
