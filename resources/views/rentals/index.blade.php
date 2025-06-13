@extends('layouts.main')

@section('title', 'Daftar Rental')

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
                                    <i class="fas fa-car text-primary me-3"></i>
                                    Daftar Rental
                                </h1>
                                <p class="text-muted mb-1">
                                    Temukan rental terpercaya di seluruh Indonesia
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Total {{ $rentals->total() }} rental terdaftar
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('rentals.index') }}" id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Cari Rental</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Nama rental...">
                                </div>
                                <div class="col-md-3">
                                    <label for="location" class="form-label">Lokasi</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="location" 
                                           name="location" 
                                           value="{{ request('location') }}" 
                                           placeholder="Kota/Kabupaten...">
                                </div>
                                <div class="col-md-3">
                                    <label for="entity_type" class="form-label">Badan Hukum</label>
                                    <select class="form-select" id="entity_type" name="entity_type">
                                        <option value="">Semua</option>
                                        <option value="PT" {{ request('entity_type') == 'PT' ? 'selected' : '' }}>PT</option>
                                        <option value="CV" {{ request('entity_type') == 'CV' ? 'selected' : '' }}>CV</option>
                                        <option value="UD" {{ request('entity_type') == 'UD' ? 'selected' : '' }}>UD</option>
                                        <option value="Perorangan" {{ request('entity_type') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(request()->hasAny(['search', 'location', 'entity_type']))
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>Reset Filter
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental Cards -->
        <div class="row">
            @forelse($rentals as $rental)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 rental-card">
                        <div class="card-body d-flex flex-column">
                            <!-- Logo -->
                            <div class="text-center mb-3">
                                @if($rental->logo)
                                    <img src="{{ asset('storage/rentals/logos/' . $rental->logo) }}" 
                                         alt="{{ $rental->company_name }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 80px; max-width: 120px; object-fit: contain;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="height: 80px; width: 120px; margin: 0 auto;">
                                        <i class="fas fa-car fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Rental Name -->
                            <h5 class="card-title text-center mb-3">
                                @if($rental->entity_type && $rental->entity_type !== 'Perorangan')
                                    <span class="badge bg-primary me-2">{{ $rental->entity_type }}</span>
                                @endif
                                {{ $rental->company_name }}
                            </h5>

                            <!-- Location -->
                            <div class="mb-3 flex-grow-1">
                                <div class="d-flex align-items-center text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <span>{{ $rental->company_address ?? 'Lokasi tidak tersedia' }}</span>
                                </div>
                                
                                @if($rental->company_phone)
                                    <div class="d-flex align-items-center text-muted mb-2">
                                        <i class="fas fa-phone me-2"></i>
                                        <span>{{ $rental->company_phone }}</span>
                                    </div>
                                @endif

                                @if($rental->company_email)
                                    <div class="d-flex align-items-center text-muted mb-2">
                                        <i class="fas fa-envelope me-2"></i>
                                        <span>{{ $rental->company_email }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Website -->
                            @if($rental->website)
                                <div class="mt-auto">
                                    <a href="{{ $rental->website }}" 
                                       target="_blank" 
                                       rel="nofollow noopener" 
                                       class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Kunjungi Website
                                    </a>
                                </div>
                            @endif

                            <!-- Verification Badge -->
                            <div class="mt-2 text-center">
                                @if($rental->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada rental ditemukan</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'location', 'entity_type']))
                                    Coba ubah filter pencarian Anda atau 
                                    <a href="{{ route('rentals.index') }}" class="text-decoration-none">reset filter</a>
                                @else
                                    Belum ada rental yang terdaftar di sistem
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rentals->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $rentals->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.rental-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.rental-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on select change
    $('#entity_type').on('change', function() {
        $('#filterForm').submit();
    });
    
    // Add loading state to search button
    $('#filterForm').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');
        submitBtn.prop('disabled', true);
    });
});
</script>
@endpush
