@extends('layouts.main')

@section('title', 'Beli Paket Sponsor - ' . $sponsorPackage->name)

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <h2 class="fw-bold text-primary mb-2">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Beli Paket Sponsor
                        </h2>
                        <p class="text-muted mb-0">Konfirmasi pembelian paket sponsor Anda</p>
                    </div>
                </div>

                <!-- Package Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-gift me-2"></i>
                            Detail Paket
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="fw-bold text-primary mb-2">
                                    {{ $sponsorPackage->name }}
                                    @if($sponsorPackage->is_popular)
                                        <span class="badge bg-warning ms-2">
                                            <i class="fas fa-star"></i> Populer
                                        </span>
                                    @endif
                                </h4>
                                
                                @if($sponsorPackage->description)
                                    <p class="text-muted mb-3">{{ $sponsorPackage->description }}</p>
                                @endif

                                <h6 class="fw-bold mb-2">Keuntungan yang Didapat:</h6>
                                <ul class="list-unstyled">
                                    @foreach($sponsorPackage->benefits as $benefit)
                                        <li class="mb-1">
                                            <i class="fas fa-check text-success me-2"></i>
                                            {{ $benefit }}
                                        </li>
                                    @endforeach
                                </ul>

                                <h6 class="fw-bold mb-2">Opsi Penempatan:</h6>
                                <div class="row">
                                    @foreach($sponsorPackage->placement_options as $placement)
                                        <div class="col-md-6 mb-1">
                                            <span class="badge bg-primary">
                                                @switch($placement)
                                                    @case('home_top')
                                                        Beranda Atas
                                                        @break
                                                    @case('home_bottom')
                                                        Beranda Bawah
                                                        @break
                                                    @case('footer')
                                                        Footer
                                                        @break
                                                    @case('sidebar')
                                                        Sidebar
                                                        @break
                                                    @default
                                                        {{ ucfirst($placement) }}
                                                @endswitch
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-3">
                                    <h6 class="fw-bold mb-2">Ringkasan Pembelian</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Harga Paket:</span>
                                        <span class="fw-bold">{{ $sponsorPackage->formatted_price }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Masa Berlaku:</span>
                                        <span>{{ $sponsorPackage->formatted_duration }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total:</span>
                                        <span class="fw-bold text-primary fs-5">{{ $sponsorPackage->formatted_price }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-contract me-2"></i>
                            Konfirmasi Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('sponsorship.simpan', $sponsorPackage) }}" method="POST">
                            @csrf
                            
                            <!-- Terms and Conditions -->
                            <div class="alert alert-info">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Syarat dan Ketentuan
                                </h6>
                                <ul class="mb-0 small">
                                    <li>Pembayaran harus dilakukan dalam waktu 24 jam setelah pembelian</li>
                                    <li>Logo sponsor harus sesuai dengan ukuran yang direkomendasikan ({{ $sponsorPackage->recommended_logo_size }} px)</li>
                                    <li>Ukuran file logo maksimal {{ number_format($sponsorPackage->max_logo_size_kb) }} KB</li>
                                    <li>Konten sponsor harus sesuai dengan kebijakan platform</li>
                                    <li>Masa berlaku sponsor dimulai setelah pembayaran dikonfirmasi admin</li>
                                    <li>Tidak ada refund setelah pembayaran dikonfirmasi</li>
                                </ul>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input @error('agree_terms') is-invalid @enderror" 
                                       type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
                                <label class="form-check-label" for="agree_terms">
                                    Saya menyetujui syarat dan ketentuan di atas
                                </label>
                                @error('agree_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('sponsor.kemitraan') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Lanjutkan ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
