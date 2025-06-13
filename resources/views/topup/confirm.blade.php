@extends('layouts.main')

@section('title', 'Konfirmasi Pembayaran Topup')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white border-0">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Konfirmasi Pembayaran
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Invoice Number</h6>
                            <p class="fw-bold">{{ $topupRequest->invoice_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-{{ $topupRequest->status_color }}">
                                {{ $topupRequest->status_text }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Jumlah Topup</h6>
                            <p class="fw-bold text-success">{{ $topupRequest->formatted_amount }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Metode Pembayaran</h6>
                            <p class="fw-bold">{{ $topupRequest->payment_method }}</p>
                            @if($topupRequest->payment_channel)
                                <small class="text-muted">{{ $topupRequest->payment_channel }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($topupRequest->payment_method === 'manual')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Instruksi Pembayaran Manual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Silakan transfer ke salah satu rekening berikut:</strong>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Bank BCA</h6>
                                    <p class="card-text">
                                        <strong>No. Rekening:</strong> 6050381330<br>
                                        <strong>Atas Nama:</strong> ANGGA DWY SAPUTRA
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Bank BJB</h6>
                                    <p class="card-text">
                                        <strong>No. Rekening:</strong> 12345869594939<br>
                                        <strong>Atas Nama:</strong> ANGGA DWY SAPUTRA
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Bank BRI</h6>
                                    <p class="card-text">
                                        <strong>No. Rekening:</strong> 208319382834<br>
                                        <strong>Atas Nama:</strong> ANGGA DWY SAPUTRA
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">E-Wallet</h6>
                                    <p class="card-text">
                                        <strong>GoPay/Dana:</strong> 0819-1191-9993<br>
                                        <strong>OVO:</strong> 0822-1121-9993<br>
                                        <strong>Atas Nama:</strong> ANGGA DWY SAPUTRA
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <strong>Penting:</strong>
                        <ul class="mb-0">
                            <li>Transfer sesuai dengan jumlah yang tertera</li>
                            <li>Simpan bukti transfer</li>
                            <li>Upload bukti transfer di bawah ini</li>
                            <li>Pembayaran akan dikonfirmasi dalam 1x24 jam</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Upload Proof -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-upload me-2"></i>
                        Upload Bukti Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('topup.upload-proof', $topupRequest->invoice_number) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="proof_of_payment" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="proof_of_payment" name="proof_of_payment"
                                   accept="image/*,.pdf" required>
                            <div class="form-text">Format yang didukung: JPG, PNG, PDF (Max: 2MB)</div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                      placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('topup.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Upload Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($topupRequest->payment_method === 'auto')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-robot me-2"></i>
                        Pembayaran Otomatis
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Fitur pembayaran otomatis akan segera tersedia
                    </div>
                    <a href="{{ route('topup.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Topup
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
