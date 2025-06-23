@extends('layouts.main')

@section('title', 'Pembayaran Donasi - CekPenyewa.com')

@section('content')
<!-- Payment Header -->
<section class="py-4" style="background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h2 class="fw-bold mb-2">
                    <i class="fas fa-credit-card me-2"></i>
                    Pembayaran Donasi
                </h2>
                <p class="mb-0 opacity-90">Silakan lakukan pembayaran sesuai instruksi di bawah</p>
            </div>
        </div>
    </div>
</section>

<!-- Payment Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Donation Summary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            Ringkasan Donasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> {{ $donation->donor_name }}</p>
                                @if($donation->company_name)
                                    <p><strong>Perusahaan:</strong> {{ $donation->company_name }}</p>
                                @endif
                                <p><strong>Email:</strong> {{ $donation->donor_email }}</p>
                                <p><strong>Telepon:</strong> {{ $donation->donor_phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Domisili:</strong> {{ $donation->donor_city }}, {{ $donation->donor_province }}</p>
                                <p><strong>Jumlah Donasi:</strong> <span class="text-primary fw-bold fs-4">{{ $donation->formatted_amount }}</span></p>
                                @if($donation->message)
                                    <p><strong>Pesan:</strong> {{ $donation->message }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Pilih Metode Pembayaran
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Bank Transfer -->
                            <div class="col-md-6">
                                <div class="card h-100 border-2 payment-method" data-method="bank">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-university fa-3x text-primary mb-3"></i>
                                        <h5 class="fw-bold">Transfer Bank</h5>
                                        <p class="text-muted">BCA, BRI, BJB</p>
                                        <button type="button" class="btn btn-outline-primary" onclick="selectPayment('bank')">
                                            Pilih Metode Ini
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- E-Wallet -->
                            <div class="col-md-6">
                                <div class="card h-100 border-2 payment-method" data-method="ewallet">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-mobile-alt fa-3x text-success mb-3"></i>
                                        <h5 class="fw-bold">E-Wallet</h5>
                                        <p class="text-muted">GoPay, Dana, OVO</p>
                                        <button type="button" class="btn btn-outline-success" onclick="selectPayment('ewallet')">
                                            Pilih Metode Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Details -->
                        <div id="bank-details" class="payment-details mt-4" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Instruksi Transfer Bank
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" style="height: 30px;" class="mb-2">
                                                <p class="mb-1"><strong>BCA</strong></p>
                                                <p class="mb-0 fw-bold">6050381330</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" style="height: 30px;" class="mb-2">
                                                <p class="mb-1"><strong>BRI</strong></p>
                                                <p class="mb-0 fw-bold">208319382834</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-primary text-white rounded p-2 mb-2" style="height: 30px; line-height: 14px;">
                                                    <strong>BJB</strong>
                                                </div>
                                                <p class="mb-1"><strong>BJB</strong></p>
                                                <p class="mb-0 fw-bold">12345869594939</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- E-Wallet Details -->
                        <div id="ewallet-details" class="payment-details mt-4" style="display: none;">
                            <div class="alert alert-success">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Instruksi E-Wallet
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-success text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fab fa-google-pay"></i>
                                                </div>
                                                <p class="mb-1"><strong>GoPay</strong></p>
                                                <p class="mb-0 fw-bold">0819-1191-9993</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-info text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <strong>D</strong>
                                                </div>
                                                <p class="mb-1"><strong>Dana</strong></p>
                                                <p class="mb-0 fw-bold">0819-1191-9993</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-purple text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #4c3494;">
                                                    <strong>O</strong>
                                                </div>
                                                <p class="mb-1"><strong>OVO</strong></p>
                                                <p class="mb-0 fw-bold">0822-1121-9993</p>
                                                <small class="text-muted">a.n ANGGA DWY SAPUTRA</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Form -->
                        <div id="confirmation-form" style="display: none;">
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">Konfirmasi Pembayaran</h6>
                            <form action="{{ route('donasi.konfirmasi-pembayaran', $donation) }}" method="POST">
                                @csrf
                                <input type="hidden" id="selected_payment_method" name="payment_method" value="">
                                
                                <div class="mb-3">
                                    <label for="payment_reference" class="form-label">Nomor Referensi/ID Transaksi <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="payment_reference" 
                                           name="payment_reference" 
                                           placeholder="Masukkan nomor referensi pembayaran"
                                           required>
                                    <small class="form-text text-muted">
                                        Masukkan nomor referensi yang Anda dapatkan setelah melakukan pembayaran
                                    </small>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Penting:</strong> Pastikan Anda telah melakukan pembayaran sebelum mengklik tombol konfirmasi. 
                                    Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('donasi.indeks') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>
                                        Konfirmasi Pembayaran
                                    </button>
                                </div>
                            </form>
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
function selectPayment(method) {
    // Hide all payment details
    $('.payment-details').hide();
    
    // Remove active class from all payment methods
    $('.payment-method').removeClass('border-primary border-success');
    
    // Show selected payment details
    if (method === 'bank') {
        $('#bank-details').show();
        $('.payment-method[data-method="bank"]').addClass('border-primary');
        $('#selected_payment_method').val('Transfer Bank');
    } else if (method === 'ewallet') {
        $('#ewallet-details').show();
        $('.payment-method[data-method="ewallet"]').addClass('border-success');
        $('#selected_payment_method').val('E-Wallet');
    }
    
    // Show confirmation form
    $('#confirmation-form').show();
    
    // Scroll to confirmation form
    $('html, body').animate({
        scrollTop: $('#confirmation-form').offset().top - 100
    }, 500);
}

// Copy account number to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Nomor rekening berhasil disalin!');
    });
}

// Add click handlers for account numbers
$(document).ready(function() {
    $('.fw-bold').click(function() {
        const text = $(this).text();
        if (text.match(/^\d+$/)) {
            copyToClipboard(text);
        }
    });
});
</script>
@endpush
