@extends('layouts.main')

@section('title', 'Sponsor Kami')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-6 fw-bold text-dark mb-2">
                                    <i class="fas fa-handshake text-primary me-3"></i>
                                    Sponsor Kami
                                </h1>
                                <p class="text-muted mb-1">
                                    Partner yang mendukung sistem blacklist rental Indonesia
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-heart text-danger me-1"></i>
                                    Terima kasih atas dukungan Anda
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('sponsors.sponsorship') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Jadi Sponsor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($sponsors->count() > 0)
            <!-- Sponsors Grid -->
            <div class="row g-4">
                @foreach($sponsors as $sponsor)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="{{ $sponsor->logo_url }}" 
                                     alt="{{ $sponsor->name }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 120px; max-width: 200px;">
                            </div>
                            <h5 class="card-title fw-bold">{{ $sponsor->name }}</h5>
                            @if($sponsor->description)
                                <p class="card-text text-muted small">{{ $sponsor->description }}</p>
                            @endif
                            <a href="{{ $sponsor->website_url }}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Kunjungi Website
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- CTA Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body text-center py-5">
                            <h3 class="fw-bold mb-3">
                                <i class="fas fa-star me-2"></i>
                                Mau Logo Rental Anda Tampil di Sini?
                            </h3>
                            <p class="lead mb-4">
                                Bergabunglah dengan sponsor kami dan dukung sistem blacklist rental Indonesia
                            </p>
                            <a href="{{ route('sponsors.sponsorship') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-phone me-2"></i>
                                Hubungi Kami Sekarang!
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Sponsors -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-handshake text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="fw-bold text-muted mb-3">Belum Ada Sponsor</h3>
                            <p class="text-muted mb-4">
                                Kami sedang mencari partner untuk mendukung sistem blacklist rental Indonesia
                            </p>
                            <a href="{{ route('sponsors.sponsorship') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Jadi Sponsor Pertama
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
