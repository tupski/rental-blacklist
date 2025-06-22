@extends('layouts.main')

@section('title', 'Jadi Sponsor')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center py-5">
                        <h1 class="display-5 fw-bold mb-3">
                            <i class="fas fa-rocket me-3"></i>
                            Jadi Sponsor Kami!
                        </h1>
                        <p class="lead mb-0">
                            Dukung sistem blacklist rental Indonesia dan tingkatkan brand awareness Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Benefits -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-gift text-primary me-2"></i>
                            Keuntungan Menjadi Sponsor
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-eye text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Brand Exposure</h6>
                                <p class="text-muted small mb-0">Logo Anda akan tampil di semua halaman website yang dikunjungi ribuan pengusaha rental setiap hari</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                <i class="fas fa-users text-success"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Target Audience</h6>
                                <p class="text-muted small mb-0">Jangkau pengusaha rental di seluruh Indonesia yang merupakan target market yang tepat</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                                <i class="fas fa-link text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Backlink Berkualitas</h6>
                                <p class="text-muted small mb-0">Dapatkan backlink berkualitas tinggi untuk meningkatkan SEO website Anda</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                                <i class="fas fa-heart text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Dukungan Sosial</h6>
                                <p class="text-muted small mb-0">Tunjukkan komitmen Anda mendukung ekosistem rental yang sehat di Indonesia</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                                <i class="fas fa-chart-line text-danger"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">ROI Terukur</h6>
                                <p class="text-muted small mb-0">Dapatkan laporan traffic dan engagement untuk mengukur efektivitas sponsorship</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-tags text-success me-2"></i>
                            Paket Sponsorship
                        </h4>
                    </div>
                    <div class="card-body">
                        @forelse($sponsorPackages as $package)
                            <div class="border rounded p-3 mb-3 {{ $package->is_popular ? 'bg-primary bg-opacity-5' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold {{ $package->is_popular ? 'text-primary' : '' }} mb-0">
                                        {{ $package->name }}
                                    </h6>
                                    @if($package->is_popular)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star"></i> Populer
                                        </span>
                                    @endif
                                </div>

                                @if($package->description)
                                    <p class="small text-muted mb-2">{{ $package->description }}</p>
                                @endif

                                <ul class="list-unstyled small text-muted mb-3">
                                    @foreach($package->benefits as $benefit)
                                        <li><i class="fas fa-check text-success me-2"></i>{{ $benefit }}</li>
                                    @endforeach
                                </ul>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="{{ $package->is_popular ? 'text-primary' : '' }} fw-bold">
                                        {{ $package->formatted_price }}
                                        <small class="text-muted fw-normal">/ {{ $package->formatted_duration }}</small>
                                    </div>

                                    @auth
                                        @if(auth()->user()->role === 'pengusaha_rental')
                                            <a href="{{ route('sponsorship.beli', $package) }}"
                                               class="btn {{ $package->is_popular ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                                <i class="fas fa-shopping-cart me-1"></i>
                                                Beli Sekarang
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('masuk') }}"
                                           class="btn {{ $package->is_popular ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            Login untuk Beli
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada paket sponsorship tersedia</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h3 class="fw-bold mb-3">Tertarik Menjadi Sponsor?</h3>
                        <p class="text-muted mb-4">
                            Hubungi tim kami untuk mendiskusikan paket sponsorship yang sesuai dengan kebutuhan bisnis Anda
                        </p>

                        <div class="row g-3 justify-content-center">
                            <div class="col-md-4">
                                <a href="https://wa.me/6281234567890?text=Halo, saya tertarik menjadi sponsor RentalGuard"
                                   target="_blank" class="btn btn-success btn-lg w-100">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    WhatsApp
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="mailto:sponsor@rentalguard.id?subject=Sponsorship Inquiry"
                                   class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-envelope me-2"></i>
                                    Email
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="tel:+6281234567890" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-phone me-2"></i>
                                    Telepon
                                </a>
                            </div>
                        </div>

                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Tim kami akan merespons dalam 24 jam
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Statistik Website
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="fw-bold text-primary">10K+</h4>
                                    <small class="text-muted">Pengunjung/Bulan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="fw-bold text-success">500+</h4>
                                    <small class="text-muted">Rental Terdaftar</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="fw-bold text-warning">5K+</h4>
                                    <small class="text-muted">Pencarian/Hari</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h4 class="fw-bold text-info">95%</h4>
                                <small class="text-muted">Pengusaha Rental</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
