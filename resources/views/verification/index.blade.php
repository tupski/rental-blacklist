@extends('layouts.main')

@section('title', 'Verifikasi Dokumen')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <i class="fas fa-shield-alt text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="display-5 fw-bold text-dark mb-3">Verifikasi Dokumen</h1>
                    <p class="lead text-muted">
                        Masukkan kode verifikasi untuk memastikan keaslian dokumen laporan blacklist
                    </p>
                </div>

                <!-- Alert Messages -->
                @if(request('kode'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-qrcode me-2"></i>
                        <strong>QR Code Terdeteksi!</strong> Kode verifikasi telah diisi otomatis. Klik "Verifikasi Dokumen" untuk melanjutkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Verification Form -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('verifikasi.verify') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="verification_code" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-2 text-primary"></i>
                                    Kode Verifikasi
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg @error('verification_code') is-invalid @enderror"
                                    id="verification_code"
                                    name="verification_code"
                                    placeholder="Contoh: ABC12345-DEF67890-GHI12345"
                                    value="{{ old('verification_code', request('kode')) }}"
                                    style="font-family: 'Courier New', monospace; letter-spacing: 1px;"
                                    required
                                >
                                @error('verification_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Kode verifikasi terdapat pada dokumen yang dicetak atau PDF yang diunduh
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>
                                    Verifikasi Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="card border-0 bg-primary text-white h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-question-circle fa-2x me-3"></i>
                                    <h5 class="card-title mb-0">Apa itu Kode Verifikasi?</h5>
                                </div>
                                <p class="card-text">
                                    Kode verifikasi adalah kode unik yang terdapat pada setiap dokumen laporan blacklist
                                    yang dicetak atau diunduh dari sistem kami untuk memastikan keaslian dokumen.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-success text-white h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                    <h5 class="card-title mb-0">Dimana Menemukan Kode?</h5>
                                </div>
                                <p class="card-text">
                                    Kode verifikasi dapat ditemukan dalam bentuk barcode dan teks pada bagian footer
                                    dokumen laporan blacklist yang telah dicetak atau diunduh.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="alert alert-info mt-4" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading">Keamanan Dokumen</h6>
                            <p class="mb-0">
                                Setiap dokumen yang diverifikasi akan tercatat dalam sistem kami untuk keperluan audit.
                                Pastikan Anda hanya memverifikasi dokumen yang sah dan diperlukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto format verification code input
    $('#verification_code').on('input', function() {
        let value = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');

        // Add dashes every 8 characters
        if (value.length > 8) {
            value = value.substring(0, 8) + '-' + value.substring(8);
        }
        if (value.length > 17) {
            value = value.substring(0, 17) + '-' + value.substring(17);
        }

        // Limit to 26 characters (8-8-8 + 2 dashes)
        if (value.length > 26) {
            value = value.substring(0, 26);
        }

        $(this).val(value);
    });

    // Auto submit form if code is provided via QR Code
    @if(request('kode'))
        setTimeout(function() {
            $('form').submit();
        }, 2000); // Wait 2 seconds to show the alert
    @endif

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endpush
