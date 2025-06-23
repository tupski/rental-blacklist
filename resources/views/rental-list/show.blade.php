@extends('layouts.main')

@section('title', 'Profil ' . $rental->name . ' - CekPenyewa.com')

@section('meta')
<meta name="description" content="Profil lengkap {{ $rental->name }} - rental terpercaya yang terdaftar di platform CekPenyewa.com">
<meta name="keywords" content="{{ $rental->name }}, rental terpercaya, {{ $rental->city ?? '' }}, {{ $rental->province ?? '' }}">
@endsection

@section('content')
<!-- Header Section -->
<section class="py-5" style="background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center text-white">
                    <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($rental->name, 0, 1)) }}
                    </div>
                    <h1 class="display-5 fw-bold mb-2">{{ $rental->name }}</h1>
                    @if($rental->company_name)
                        <p class="lead opacity-90 mb-3">{{ $rental->company_name }}</p>
                    @endif

                    <!-- Badges -->
                    <div class="mb-4">
                        @php
                            $now = now();
                            $oneMonthAgo = $now->copy()->subMonth();

                            // Check badges
                            $isSponsor = false;
                            $isDonatur = false;
                            $isTopReporter = false;
                            $reportsCount = 0;

                            try {
                                $isSponsor = $rental->sponsors()->where('status', 'active')->exists();
                                $isDonatur = \App\Models\Donation::where('donor_email', $rental->email)
                                                                ->where('status', 'confirmed')
                                                                ->where('confirmed_at', '>=', $oneMonthAgo)
                                                                ->exists();
                                $reportsCount = $rental->rentalBlacklists()->where('created_at', '>=', $oneMonthAgo)->count();
                                $isTopReporter = $reportsCount >= 5;
                            } catch (\Exception $e) {
                                // Handle missing relations gracefully
                            }
                        @endphp

                        @if($isSponsor)
                            <span class="badge bg-warning text-dark me-2 mb-2 fs-6">
                                <i class="fas fa-crown me-1"></i>
                                Sponsor Resmi
                            </span>
                        @endif

                        @if($isDonatur)
                            <span class="badge bg-success me-2 mb-2 fs-6">
                                <i class="fas fa-heart me-1"></i>
                                Donatur
                            </span>
                        @endif

                        @if($isTopReporter)
                            <span class="badge bg-info me-2 mb-2 fs-6">
                                <i class="fas fa-trophy me-1"></i>
                                Top Reporter
                            </span>
                        @endif

                        <span class="badge bg-light text-dark me-2 mb-2 fs-6">
                            <i class="fas fa-shield-check me-1"></i>
                            Terverifikasi
                        </span>
                    </div>

                    @if($rental->city || $rental->province)
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            @if($rental->city){{ $rental->city }}@endif
                            @if($rental->city && $rental->province), @endif
                            @if($rental->province){{ $rental->province }}@endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- About Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Tentang {{ $rental->name }}
                        </h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Nama Lengkap</small>
                                        <strong>{{ $rental->name }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($rental->company_name)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Perusahaan</small>
                                        <strong>{{ $rental->company_name }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="fas fa-calendar text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Bergabung</small>
                                        <strong>{{ $rental->created_at->format('d F Y') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Total Laporan</small>
                                        <strong>{{ $reportsCount }} Laporan</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                @if($rental->phone || $rental->website || $rental->email)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">
                            <i class="fas fa-phone text-primary me-2"></i>
                            Kontak
                        </h4>

                        <div class="row g-3">
                            @if($rental->phone)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-whatsapp text-success me-3 fa-lg"></i>
                                        <div>
                                            <small class="text-muted d-block">WhatsApp</small>
                                            <strong>{{ $rental->phone }}</strong>
                                        </div>
                                    </div>
                                    <a href="https://wa.me/{{ $rental->phone }}"
                                       target="_blank"
                                       class="btn btn-success btn-sm">
                                        <i class="fab fa-whatsapp me-1"></i>
                                        Chat
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($rental->website)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-globe text-primary me-3 fa-lg"></i>
                                        <div>
                                            <small class="text-muted d-block">Website</small>
                                            <strong>{{ parse_url($rental->website, PHP_URL_HOST) }}</strong>
                                        </div>
                                    </div>
                                    <a href="{{ $rental->website }}"
                                       target="_blank"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        Kunjungi
                                    </a>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-envelope text-secondary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $rental->email }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Stats Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Statistik</h5>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-primary bg-opacity-10 rounded">
                                    <div class="h4 fw-bold text-primary mb-1">{{ $reportsCount }}</div>
                                    <small class="text-muted">Total Laporan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <div class="h4 fw-bold text-success mb-1">{{ $rental->created_at->diffInMonths(now()) }}</div>
                                    <small class="text-muted">Bulan Aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Ingin Bergabung?</h5>
                        <p class="text-muted mb-3">
                            Daftarkan rental Anda dan dapatkan akses ke database blacklist pelanggan bermasalah.
                        </p>
                        <a href="{{ route('daftar') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Daftar Rental Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Back to List -->
<section class="py-3 bg-light">
    <div class="container">
        <div class="text-center">
            <a href="{{ route('daftar-rental.indeks') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Daftar Rental
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.8rem;
}
</style>
@endpush
