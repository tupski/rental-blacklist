@extends('layouts.main')

@section('title', 'Donasi untuk Proyek CekPenyewa.com')

@section('meta')
<meta name="description" content="Dukung pengembangan platform CekPenyewa.com dengan berdonasi. Bantu kami melindungi komunitas rental Indonesia dari pelanggan bermasalah.">
<meta name="keywords" content="donasi, dukungan, cekpenyewa, rental, blacklist, indonesia">
<meta property="og:title" content="Donasi untuk Proyek CekPenyewa.com">
<meta property="og:description" content="Dukung pengembangan platform CekPenyewa.com dengan berdonasi. Bantu kami melindungi komunitas rental Indonesia.">
<meta property="og:type" content="website">
@endsection

@section('content')
<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-heart me-3"></i>
                    Dukung Proyek CekPenyewa.com
                </h1>
                <p class="lead opacity-90 mb-4">
                    Bantu kami mengembangkan platform yang melindungi komunitas rental Indonesia dari pelanggan bermasalah. 
                    Setiap donasi Anda sangat berarti untuk keberlanjutan proyek ini.
                </p>
                <div class="d-flex justify-content-center align-items-center gap-4 mt-4">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                        <p class="mb-0 small">Perlindungan</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p class="mb-0 small">Komunitas</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-rocket fa-2x mb-2"></i>
                        <p class="mb-0 small">Inovasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Donate Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="text-center mb-5">Mengapa Donasi Anda Penting?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-server text-primary fa-2x mt-2"></i>
                                </div>
                                <h5 class="fw-bold mb-3">Infrastruktur Server</h5>
                                <p class="text-muted">
                                    Biaya hosting, domain, dan infrastruktur cloud untuk menjaga platform tetap online 24/7.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-code text-success fa-2x mt-2"></i>
                                </div>
                                <h5 class="fw-bold mb-3">Pengembangan Fitur</h5>
                                <p class="text-muted">
                                    Pengembangan fitur baru seperti AI moderation, verifikasi otomatis, dan integrasi API.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-headset text-warning fa-2x mt-2"></i>
                                </div>
                                <h5 class="fw-bold mb-3">Dukungan Operasional</h5>
                                <p class="text-muted">
                                    Tim support, moderasi konten, dan maintenance untuk menjaga kualitas platform.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-hand-holding-heart me-2"></i>
                            Form Donasi
                        </h3>
                        <p class="mb-0 opacity-90">Isi data Anda untuk melanjutkan donasi</p>
                    </div>

                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('donasi.simpan') }}" method="POST" id="donationForm">
                            @csrf
                            
                            <!-- Donor Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tipe Donatur <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="donor_type" id="personal" value="personal" checked>
                                            <label class="form-check-label" for="personal">
                                                <i class="fas fa-user me-2"></i>
                                                Pribadi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="donor_type" id="company" value="company">
                                            <label class="form-check-label" for="company">
                                                <i class="fas fa-building me-2"></i>
                                                Perusahaan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Donor Name -->
                                    <div class="mb-3">
                                        <label for="donor_name" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('donor_name') is-invalid @enderror" 
                                               id="donor_name" 
                                               name="donor_name" 
                                               value="{{ old('donor_name') }}" 
                                               required>
                                        @error('donor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Company Name (hidden by default) -->
                                    <div class="mb-3" id="company_name_group" style="display: none;">
                                        <label for="company_name" class="form-label fw-bold">Nama Perusahaan <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" 
                                               name="company_name" 
                                               value="{{ old('company_name') }}">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="donor_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('donor_email') is-invalid @enderror" 
                                               id="donor_email" 
                                               name="donor_email" 
                                               value="{{ old('donor_email') }}" 
                                               required>
                                        @error('donor_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="donor_phone" class="form-label fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                        <input type="tel" 
                                               class="form-control @error('donor_phone') is-invalid @enderror" 
                                               id="donor_phone" 
                                               name="donor_phone" 
                                               value="{{ old('donor_phone') }}" 
                                               required>
                                        @error('donor_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Province -->
                                    <div class="mb-3">
                                        <label for="donor_province" class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('donor_province') is-invalid @enderror" 
                                                id="donor_province" 
                                                name="donor_province" 
                                                required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                        @error('donor_province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- City -->
                                    <div class="mb-3">
                                        <label for="donor_city" class="form-label fw-bold">Kabupaten/Kota <span class="text-danger">*</span></label>
                                        <select class="form-select @error('donor_city') is-invalid @enderror" 
                                                id="donor_city" 
                                                name="donor_city" 
                                                required>
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                        @error('donor_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-bold">Jumlah Donasi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           min="10000"
                                           step="1000"
                                           required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimal donasi Rp 10.000</small>
                                
                                <!-- Quick Amount Buttons -->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="setAmount(25000)">Rp 25.000</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="setAmount(50000)">Rp 50.000</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="setAmount(100000)">Rp 100.000</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(250000)">Rp 250.000</button>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Pesan (Opsional)</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="3" 
                                          placeholder="Tulis pesan dukungan Anda...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-heart me-2"></i>
                                    Lanjutkan ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="fw-bold mb-4">Dampak Donasi Anda</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="rounded-circle bg-success text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <strong>1K+</strong>
                            </div>
                            <h6 class="fw-bold">Rental Terlindungi</h6>
                            <p class="text-muted small">Bisnis rental yang terhindar dari kerugian</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <strong>5K+</strong>
                            </div>
                            <h6 class="fw-bold">Data Terverifikasi</h6>
                            <p class="text-muted small">Laporan blacklist yang telah divalidasi</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="rounded-circle bg-warning text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <strong>24/7</strong>
                            </div>
                            <h6 class="fw-bold">Layanan Aktif</h6>
                            <p class="text-muted small">Platform selalu siap melayani komunitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load provinces
    loadProvinces();
    
    // Donor type toggle
    $('input[name="donor_type"]').change(function() {
        if ($(this).val() === 'company') {
            $('#company_name_group').show();
            $('#company_name').attr('required', true);
        } else {
            $('#company_name_group').hide();
            $('#company_name').attr('required', false);
        }
    });

    // Province change
    $('#donor_province').change(function() {
        const province = $(this).val();
        if (province) {
            loadCities(province);
        } else {
            $('#donor_city').html('<option value="">Pilih Kabupaten/Kota</option>');
        }
    });
});

function loadProvinces() {
    $.get('{{ route("donasi.provinces") }}', function(data) {
        let options = '<option value="">Pilih Provinsi</option>';
        data.forEach(function(province) {
            options += `<option value="${province}">${province}</option>`;
        });
        $('#donor_province').html(options);
    });
}

function loadCities(province) {
    $.get('{{ route("donasi.cities") }}', {province: province}, function(data) {
        let options = '<option value="">Pilih Kabupaten/Kota</option>';
        data.forEach(function(city) {
            options += `<option value="${city}">${city}</option>`;
        });
        $('#donor_city').html(options);
    });
}

function setAmount(amount) {
    $('#amount').val(amount);
}
</script>
@endpush
