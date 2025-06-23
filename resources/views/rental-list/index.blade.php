@extends('layouts.main')

@section('title', 'Daftar Rental Terdaftar - CekPenyewa.com')

@section('meta')
<meta name="description" content="Daftar lengkap rental mobil, motor, dan kamera yang sudah terdaftar dan terverifikasi di platform CekPenyewa.com">
<meta name="keywords" content="daftar rental, rental terdaftar, rental mobil, rental motor, rental kamera, cekpenyewa">
@endsection

@section('content')
<!-- Header Section -->
<section class="py-5" style="background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-store me-3"></i>
                    Daftar Rental Terdaftar
                </h1>
                <p class="lead opacity-90 mb-4">
                    Temukan rental terpercaya yang sudah terdaftar dan terverifikasi di platform CekPenyewa.com
                </p>
                <div class="d-flex justify-content-center align-items-center gap-4 mt-4">
                    <div class="text-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                            <i class="fas fa-shield-check fa-lg mt-2"></i>
                        </div>
                        <small>Terverifikasi</small>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                            <i class="fas fa-star fa-lg mt-2"></i>
                        </div>
                        <small>Terpercaya</small>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                            <i class="fas fa-handshake fa-lg mt-2"></i>
                        </div>
                        <small>Profesional</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('daftar-rental.indeks') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Cari Rental</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ $search }}"
                                   placeholder="Nama rental, perusahaan, atau email">
                        </div>
                        <div class="col-md-3">
                            <label for="province" class="form-label">Provinsi</label>
                            <select class="form-select" id="province" name="province">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ $province === $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">Kota/Kabupaten</label>
                            <select class="form-select" id="city" name="city">
                                <option value="">Semua Kota</option>
                                @foreach($cities as $ct)
                                    <option value="{{ $ct }}" {{ $city === $ct ? 'selected' : '' }}>
                                        {{ $ct }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="py-5">
    <div class="container">
        <!-- Results Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">
                Ditemukan {{ $rentals->total() }} Rental
                @if($search || $province || $city)
                    <small class="text-muted">
                        @if($search) untuk "{{ $search }}" @endif
                        @if($province) di {{ $province }} @endif
                        @if($city) - {{ $city }} @endif
                    </small>
                @endif
            </h3>
            
            @if($search || $province || $city)
                <a href="{{ route('daftar-rental.indeks') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <!-- Rental Cards -->
        <div class="row g-4">
            @forelse($rentals as $rental)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rental-card">
                        <div class="card-body p-4">
                            <!-- Header with Avatar -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($rental->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">{{ $rental->name }}</h5>
                                    @if($rental->company_name)
                                        <p class="text-muted small mb-0">{{ $rental->company_name }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="mb-3">
                                @php
                                    $now = now();
                                    $oneMonthAgo = $now->copy()->subMonth();
                                    
                                    // Check if sponsor (has active sponsor subscription)
                                    $isSponsor = $rental->sponsors()->where('status', 'active')->exists();
                                    
                                    // Check if donatur (donated in last month)
                                    $isDonatur = \App\Models\Donation::where('donor_email', $rental->email)
                                                                    ->where('status', 'confirmed')
                                                    ->where('confirmed_at', '>=', $oneMonthAgo)
                                                    ->exists();
                                    
                                    // Check if top reporter (most reports in last month)
                                    $reportsCount = $rental->rentalBlacklists()
                                                          ->where('created_at', '>=', $oneMonthAgo)
                                                          ->count();
                                    
                                    $topReporterThreshold = 5; // Minimum reports to be top reporter
                                    $isTopReporter = $reportsCount >= $topReporterThreshold;
                                @endphp

                                @if($isSponsor)
                                    <span class="badge bg-warning text-dark me-1 mb-1">
                                        <i class="fas fa-crown me-1"></i>
                                        Sponsor Resmi
                                    </span>
                                @endif

                                @if($isDonatur)
                                    <span class="badge bg-success me-1 mb-1">
                                        <i class="fas fa-heart me-1"></i>
                                        Donatur
                                    </span>
                                @endif

                                @if($isTopReporter)
                                    <span class="badge bg-info me-1 mb-1">
                                        <i class="fas fa-trophy me-1"></i>
                                        Top Reporter
                                    </span>
                                @endif

                                <span class="badge bg-primary me-1 mb-1">
                                    <i class="fas fa-shield-check me-1"></i>
                                    Terverifikasi
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="mb-3">
                                @if($rental->city || $rental->province)
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        @if($rental->city){{ $rental->city }}@endif
                                        @if($rental->city && $rental->province), @endif
                                        @if($rental->province){{ $rental->province }}@endif
                                    </p>
                                @endif
                                
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-calendar me-2"></i>
                                    Bergabung {{ $rental->created_at->format('M Y') }}
                                </p>
                                
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-file-alt me-2"></i>
                                    {{ $rental->rentalBlacklists()->count() }} Laporan
                                </p>
                            </div>

                            <!-- Contact Info -->
                            @if($rental->phone || $rental->website)
                                <div class="mb-3">
                                    @if($rental->phone)
                                        <a href="https://wa.me/{{ $rental->phone }}" 
                                           target="_blank" 
                                           class="btn btn-success btn-sm me-2 mb-1">
                                            <i class="fab fa-whatsapp me-1"></i>
                                            WhatsApp
                                        </a>
                                    @endif
                                    
                                    @if($rental->website)
                                        <a href="{{ $rental->website }}" 
                                           target="_blank" 
                                           class="btn btn-outline-primary btn-sm mb-1">
                                            <i class="fas fa-globe me-1"></i>
                                            Website
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="d-grid">
                                <a href="{{ route('daftar-rental.tampil', $rental) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada rental ditemukan</h4>
                        <p class="text-muted">
                            @if($search || $province || $city)
                                Coba ubah kriteria pencarian Anda atau 
                                <a href="{{ route('daftar-rental.indeks') }}">reset filter</a>
                            @else
                                Belum ada rental yang terdaftar di platform ini.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rentals->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $rentals->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="fw-bold mb-3">Ingin Bergabung?</h3>
                <p class="text-muted mb-4">
                    Daftarkan rental Anda di platform CekPenyewa.com dan dapatkan akses ke database blacklist pelanggan bermasalah.
                </p>
                <a href="{{ route('daftar') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.rental-card {
    transition: all 0.3s ease;
}

.rental-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when province changes to update cities
    $('#province').change(function() {
        // You can add AJAX here to update cities based on province
        // For now, we'll just clear the city selection
        $('#city').val('');
    });
});
</script>
@endpush
