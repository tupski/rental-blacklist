@extends('layouts.main')

@section('title', 'Detail Pembayaran - ' . $sponsorPurchase->invoice_number)

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <h2 class="fw-bold text-primary mb-2">
                            <i class="fas fa-file-invoice me-2"></i>
                            Detail Pembayaran
                        </h2>
                        <p class="text-muted mb-0">Invoice: {{ $sponsorPurchase->invoice_number }}</p>
                    </div>
                </div>

                <!-- Purchase Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Invoice:</th>
                                        <td>{{ $sponsorPurchase->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Paket:</th>
                                        <td>
                                            {{ $sponsorPurchase->sponsorPackage->name }}
                                            @if($sponsorPurchase->sponsorPackage->is_popular)
                                                <span class="badge bg-warning ms-1">
                                                    <i class="fas fa-star"></i> Populer
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah:</th>
                                        <td class="fw-bold text-primary">{{ $sponsorPurchase->formatted_amount }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{!! $sponsorPurchase->status_badge !!}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Tanggal Pembelian:</th>
                                        <td>{{ $sponsorPurchase->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Batas Pembayaran:</th>
                                        <td>
                                            @if($sponsorPurchase->payment_status === 'pending')
                                                @if($sponsorPurchase->isExpired())
                                                    <span class="text-danger fw-bold">
                                                        <i class="fas fa-times-circle"></i>
                                                        Expired
                                                    </span>
                                                @else
                                                    <span class="text-warning fw-bold">
                                                        <i class="fas fa-clock"></i>
                                                        {{ $sponsorPurchase->payment_deadline->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @if($sponsorPurchase->paid_at)
                                        <tr>
                                            <th>Tanggal Konfirmasi:</th>
                                            <td>{{ $sponsorPurchase->paid_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endif
                                    @if($sponsorPurchase->confirmed_at)
                                        <tr>
                                            <th>Tanggal Verifikasi:</th>
                                            <td>{{ $sponsorPurchase->confirmed_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        @if($sponsorPurchase->payment_notes)
                            <div class="mt-3">
                                <h6 class="fw-bold">Catatan Pembayaran:</h6>
                                <div class="alert alert-info">
                                    {{ $sponsorPurchase->payment_notes }}
                                </div>
                            </div>
                        @endif

                        @if($sponsorPurchase->admin_notes)
                            <div class="mt-3">
                                <h6 class="fw-bold">Catatan Admin:</h6>
                                <div class="alert alert-warning">
                                    {{ $sponsorPurchase->admin_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Confirmation Form -->
                @if($sponsorPurchase->payment_status === 'pending' && !$sponsorPurchase->isExpired())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-upload me-2"></i>
                                Konfirmasi Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Payment Methods -->
                            <div class="alert alert-info">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-university me-2"></i>
                                    Metode Pembayaran
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Transfer Bank:</strong><br>
                                        BCA: 6050381330<br>
                                        BJB: 12345869594939<br>
                                        BRI: 208319382834
                                    </div>
                                    <div class="col-md-6">
                                        <strong>E-Wallet:</strong><br>
                                        GoPay/Dana: 0819-1191-9993<br>
                                        OVO: 0822-1121-9993<br>
                                        <small>a.n ANGGA DWY SAPUTRA</small>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('sponsorship.konfirmasi', $sponsorPurchase) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">
                                        Bukti Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                                           id="payment_proof" name="payment_proof" accept="image/*" required>
                                    <div class="form-text">
                                        Upload screenshot atau foto bukti transfer. Format: JPG, PNG. Maksimal 5MB.
                                    </div>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="payment_notes" class="form-label">Catatan (Opsional)</label>
                                    <textarea class="form-control @error('payment_notes') is-invalid @enderror" 
                                              id="payment_notes" name="payment_notes" rows="3" 
                                              placeholder="Tambahkan catatan jika diperlukan...">{{ old('payment_notes') }}</textarea>
                                    @error('payment_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('sponsorship.pembayaran') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-check me-2"></i>
                                        Konfirmasi Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Payment Proof Display -->
                @if($sponsorPurchase->payment_proof)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-image me-2"></i>
                                Bukti Pembayaran
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ Storage::disk('public')->url($sponsorPurchase->payment_proof) }}" 
                                 alt="Bukti Pembayaran" class="img-fluid rounded shadow" style="max-height: 400px;">
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('sponsorship.pembayaran') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali ke Daftar
                            </a>
                            
                            @if($sponsorPurchase->payment_status === 'confirmed')
                                <a href="{{ route('sponsorship.pengaturan', $sponsorPurchase) }}" class="btn btn-success">
                                    <i class="fas fa-cog me-2"></i>
                                    Pengaturan Sponsor
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
