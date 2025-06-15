@extends('layouts.main')

@section('title', $settings['meta_title'])

@section('meta')
<meta name="description" content="{{ $settings['meta_description'] }}">
<meta name="keywords" content="{{ $settings['meta_keywords'] }}">
<meta property="og:title" content="{{ $settings['meta_title'] }}">
<meta property="og:description" content="{{ $settings['meta_description'] }}">
<meta property="og:type" content="website">
@endsection

@section('content')
<!-- Account Status Alert -->
@include('components.account-status-alert')

<!-- Email Verification Alert -->
@include('components.email-verification-alert')

<!-- Low Balance Alert -->
@include('components.low-balance-alert')

<!-- Hero Section -->
<div class="bg-gradient-to-br from-danger-subtle to-warning-subtle min-vh-100">
    <!-- Hero Content -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10 fade-in">
                <div class="mb-3">
                    <span class="badge bg-danger fs-6 pulse">
                        <i class="fas fa-gift me-1"></i>
                        100% GRATIS Untuk Pemilik Rental
                    </span>
                </div>
                <h1 class="display-3 fw-bold text-dark mb-4 d-none d-md-block">
                    {{ $settings['hero_title'] }}
                </h1>
                <p class="lead fs-4 text-muted mb-5 d-none d-md-block">
                    {{ $settings['hero_subtitle'] }}
                </p>

                <!-- Top Sponsors -->
                @if(isset($homeTopSponsors) && $homeTopSponsors->count() > 0)
                <div class="row justify-content-center mb-4 d-none d-md-block">
                    <div class="col-lg-8">
                        <div class="text-center">
                            <small class="text-muted d-block mb-3">Didukung oleh:</small>
                            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4">
                                @foreach($homeTopSponsors as $sponsor)
                                    <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                        <img src="{{ $sponsor->logo_url }}"
                                             alt="{{ $sponsor->name }}"
                                             class="img-fluid"
                                             style="max-height: 50px; max-width: 150px;"
                                             title="{{ $sponsor->name }}">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Search Form -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="card shadow-lg border-0">
                            <div class="card-body p-4">
                                <form id="searchForm">
                                    <div class="input-group input-group-lg">
                                        <input
                                            type="text"
                                            id="searchInput"
                                            name="cari"
                                            class="form-control border-0 shadow-none"
                                            placeholder="Masukkan NIK, Nama Lengkap, atau Nomor HP (min. 3 karakter)"
                                            required
                                            minlength="3"
                                        >
                                        <button
                                            type="submit"
                                            class="btn btn-danger px-4"
                                            id="searchBtn"
                                        >
                                            <i class="fas fa-search"></i>
                                            <span class="d-none d-md-inline ms-2">Cari Sekarang</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Sponsors -->
                @if(isset($homeBottomSponsors) && $homeBottomSponsors->count() > 0)
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light text-center">
                                <h6 class="mb-0 text-muted">
                                    <i class="fas fa-handshake me-2"></i>
                                    Sponsor Kami
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <!-- Desktop View -->
                                <div class="d-none d-md-block">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-4">
                                        @foreach($homeBottomSponsors as $sponsor)
                                            <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                                <div class="sponsor-item p-2 rounded border text-center" style="min-width: 120px;">
                                                    <img src="{{ $sponsor->logo_url }}"
                                                         alt="{{ $sponsor->name }}"
                                                         class="img-fluid mb-2"
                                                         style="max-height: 40px; max-width: 100px;"
                                                         title="{{ $sponsor->name }}">
                                                    <div class="small text-muted">{{ $sponsor->name }}</div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Mobile Slider -->
                                <div class="d-md-none">
                                    <div id="sponsorCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($homeBottomSponsors->chunk(2) as $index => $sponsorChunk)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="row g-2 justify-content-center">
                                                    @foreach($sponsorChunk as $sponsor)
                                                    <div class="col-6">
                                                        <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                                            <div class="sponsor-item p-2 rounded border text-center">
                                                                <img src="{{ $sponsor->logo_url }}"
                                                                     alt="{{ $sponsor->name }}"
                                                                     class="img-fluid mb-2"
                                                                     style="max-height: 30px; max-width: 80px;"
                                                                     title="{{ $sponsor->name }}">
                                                                <div class="small text-muted">{{ $sponsor->name }}</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        @if($homeBottomSponsors->count() > 2)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#sponsorCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#sponsorCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Laporan</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['total_laporan']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-user-times text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pelanggan Bermasalah</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['total_pelanggan_bermasalah']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-store text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Rental Terdaftar</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['rental_terdaftar']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-alt text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Laporan Bulan Ini</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['laporan_bulan_ini']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div class="container">
        <div id="loading" class="d-none text-center py-5">
            <div class="spinner-border text-danger me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-muted">Mencari data...</span>
        </div>

        <!-- Results -->
        <div id="results" class="d-none">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-1">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Hasil Pencarian
                    </h5>
                    <p class="text-muted mb-0" id="resultCount"></p>
                </div>

                <div id="resultsList" class="card-body p-0">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- No Results -->
        <div id="noResults" class="d-none text-center py-5">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <i class="fas fa-search text-muted display-1 mb-4"></i>
                    <h4 class="fw-bold mb-3">Tidak Ada Data Ditemukan</h4>
                    <p class="text-muted mb-3">Data yang Anda cari tidak ditemukan dalam database blacklist.</p>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Pelanggan ini kemungkinan aman untuk disewakan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-4">
                            <i class="fas fa-users text-primary me-2"></i>
                            Untuk Pengusaha Rental
                        </h4>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Daftar gratis dan akses penuh</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Lihat data lengkap tanpa sensor</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Tambah laporan pelanggan bermasalah</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Kelola data laporan Anda</span>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('daftar') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-4">
                            <i class="fas fa-eye text-info me-2"></i>
                            Untuk Pengguna Umum
                        </h4>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-search text-primary me-3 mt-1"></i>
                                <span>Cari data dengan NIK atau nama</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-eye-slash text-warning me-3 mt-1"></i>
                                <span>Data ditampilkan dengan sensor</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-credit-card text-success me-3 mt-1"></i>
                                <span>Beli kredit untuk lihat data lengkap</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-shield-alt text-danger me-3 mt-1"></i>
                                <span>Data terverifikasi dan terpercaya</span>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <button class="btn btn-info btn-lg">
                                <i class="fas fa-coins me-2"></i>
                                Beli Kredit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-4">Dipercaya oleh Ribuan Pengusaha Rental</h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card bg-transparent border-light text-white">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <p class="mb-3">"Sangat membantu untuk screening pelanggan sebelum menyewakan motor."</p>
                                    <small class="fw-bold">- Rental Motor Jakarta</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-transparent border-light text-white">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <p class="mb-3">"Database lengkap dan selalu update. Recommended!"</p>
                                    <small class="fw-bold">- Rental Mobil Bandung</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-transparent border-light text-white">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <p class="mb-3">"Gratis tapi kualitas premium. Terima kasih RentalGuard!"</p>
                                    <small class="fw-bold">- Rental Alat Surabaya</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-3">Siap Melindungi Bisnis Rental Anda?</h3>
                    <p class="text-muted mb-4">Bergabunglah dengan ribuan pengusaha rental yang sudah merasakan manfaatnya</p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        @guest
                            <a href="{{ route('daftar') }}" class="btn btn-danger btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Gratis Sekarang
                            </a>
                            <a href="{{ route('masuk') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sudah Punya Akun? Login
                            </a>
                        @else
                            <a href="{{ route('dasbor') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Ke Dashboard
                            </a>
                            <a href="{{ route('dasbor.daftar-hitam.buat') }}" class="btn btn-danger btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Laporan
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Blacklist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>

                <div class="d-flex gap-2">
                    @auth
                        @if(Auth::user()->role === 'user')
                            <button type="button" class="btn btn-outline-danger" id="unlockDetailBtn" onclick="confirmUnlockDetail()">
                                <i class="fas fa-eye me-2"></i>
                                Lihat Detail Lengkap
                            </button>
                        @endif
                    @else
                        <button type="button" class="btn btn-success" onclick="showFullAccess()">
                            <i class="fas fa-unlock me-2"></i>
                            Akses Penuh
                        </button>
                    @endauth

                    <!-- Print and PDF buttons (hidden by default, shown after unlock) -->
                    <button type="button" class="btn btn-info d-none" id="printDetailBtn" onclick="printDetail()">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <button type="button" class="btn btn-danger d-none" id="downloadPdfBtn" onclick="downloadPDF()">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
@if(Auth::user()->role === 'user')
<!-- Unlock Confirmation Modal -->
<div class="modal fade" id="unlockConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-unlock text-warning me-2"></i>
                    Konfirmasi Buka Detail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong> Tindakan ini akan memotong saldo Anda.
                </div>
                <p>Anda akan membuka detail lengkap untuk:</p>
                <ul>
                    <li><strong>Nama:</strong> <span id="confirm-name"></span></li>
                    <li><strong>Jenis Rental:</strong> <span id="confirm-rental"></span></li>
                    <li><strong>Biaya:</strong> <span id="confirm-price" class="text-danger fw-bold"></span></li>
                </ul>
                <p class="text-muted small">
                    Setelah membuka detail, Anda akan melihat data lengkap termasuk alamat dan kronologi kejadian.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-warning" id="confirmUnlockBtn">
                    <i class="fas fa-unlock me-2"></i>Ya, Buka Detail
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

@endsection

@push('styles')
<style>
.sponsor-item {
    transition: all 0.3s ease;
}

.sponsor-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    width: 30px;
    height: 30px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentSearch = '';
    let currentDetailId = null;
    let currentDetailData = null;

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle form submission
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    // Handle input changes for URL updates
    $('#searchInput').on('input', function() {
        const search = $(this).val();
        if (search.length >= 3) {
            updateURL(search);
        }
    });

    // Load search from URL on page load
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('cari');
    if (searchParam) {
        $('#searchInput').val(searchParam);
        performSearch();
    }

    function performSearch() {
        const search = $('#searchInput').val().trim();

        if (search.length < 3) {
            alert('Pencarian minimal 3 karakter');
            return;
        }

        currentSearch = search;
        updateURL(search);

        // Show loading
        $('#loading').removeClass('d-none');
        $('#results').addClass('d-none');
        $('#noResults').addClass('d-none');
        $('#searchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');

        // Perform AJAX search
        $.ajax({
            url: '{{ route("publik.cari") }}',
            method: 'POST',
            data: {
                cari: search,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayResults(response.data, response.total);
                } else {
                    showNoResults();
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                alert('Terjadi kesalahan saat mencari data');
            },
            complete: function() {
                $('#loading').addClass('d-none');
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Cari Sekarang');
            }
        });
    }

    function displayResults(data, total) {
        $('#resultCount').text(`Ditemukan ${total} data blacklist`);

        let html = '';
        data.forEach(function(item) {
            html += `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <h6 class="fw-bold text-dark mb-0 me-2">${item.nama_lengkap}</h6>
                                    ${item.is_verified ? '<i class="fas fa-check-circle text-primary" title="Rental Terverifikasi"></i>' : ''}
                                </div>
                                <span class="badge bg-danger">
                                    ${item.jumlah_laporan} Laporan
                                </span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-id-card me-1"></i>
                                    NIK: ${item.nik}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-phone me-1"></i>
                                    HP: ${item.no_hp}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-car me-1"></i>
                                    ${item.jenis_rental}
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-warning text-dark me-1">${item.jenis_laporan}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${item.tanggal_kejadian}
                                </small>
                                <button onclick="viewDetail(${item.id})" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#resultsList').html(`<div class="row">${html}</div>`);
        $('#results').removeClass('d-none');

        // Auto-scroll ke hasil pencarian dengan smooth animation
        setTimeout(function() {
            scrollToResults();
        }, 100);
    }

    function showNoResults() {
        $('#noResults').removeClass('d-none');

        // Auto-scroll ke no results dengan smooth animation
        setTimeout(function() {
            scrollToResults();
        }, 100);
    }

    function scrollToResults() {
        const resultsSection = document.getElementById('results');
        const noResultsSection = document.getElementById('noResults');
        const targetElement = resultsSection && !resultsSection.classList.contains('d-none') ? resultsSection : noResultsSection;

        if (targetElement) {
            // Calculate offset untuk berbagai device
            let offset = 100; // Default offset

            // Responsive offset berdasarkan screen size
            if (window.innerWidth <= 576) {
                // Mobile
                offset = 80;
            } else if (window.innerWidth <= 768) {
                // Tablet
                offset = 90;
            } else {
                // Desktop
                offset = 100;
            }

            const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
            const offsetPosition = elementPosition - offset;

            // Smooth scroll dengan behavior yang kompatibel
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    function updateURL(search) {
        const url = new URL(window.location);
        url.searchParams.set('cari', search);
        window.history.replaceState({}, '', url);
    }

    // View detail function
    window.viewDetail = function(id) {
        $.ajax({
            url: `/detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    let jenisLaporanHtml = '';
                    if (Array.isArray(data.jenis_laporan)) {
                        data.jenis_laporan.forEach(function(jenis) {
                            jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${jenis}</span>`;
                        });
                    } else {
                        jenisLaporanHtml = `<span class="badge bg-warning text-dark">${data.jenis_laporan}</span>`;
                    }

                    // Store current detail data
                    currentDetailData = data;
                    currentDetailId = data.id;

                    // Tampilkan data tanpa sensor untuk semua user
                    $('#detailContent').html(`
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Nama Lengkap</label>
                                <p class="mb-0">${data.nama_lengkap}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">NIK</label>
                                <p class="mb-0">${data.nik}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Jenis Kelamin</label>
                                <p class="mb-0">${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">No HP</label>
                                <p class="mb-0">${data.no_hp}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Jenis Rental</label>
                                <p class="mb-0">${data.jenis_rental}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Jumlah Laporan</label>
                                <p class="mb-0"><span class="badge bg-danger">${data.jumlah_laporan} Laporan</span></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Alamat Lengkap</label>
                                <p class="mb-0">${data.alamat || 'Tidak ada data'}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Jenis Laporan</label>
                                <div>${jenisLaporanHtml}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Tanggal Kejadian</label>
                                <p class="mb-0">${data.tanggal_kejadian}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Pelapor</label>
                                <p class="mb-0">
                                    ${data.pelapor_role === 'pengusaha_rental' ?
                                        `<a href="/rental/${data.pelapor_id}/profil" class="text-decoration-none fw-bold text-success">
                                            <i class="fas fa-building me-1"></i>${data.pelapor}
                                        </a>` :
                                        data.pelapor
                                    }
                                    ${data.is_verified ? '<i class="fas fa-check-circle text-primary ms-2" title="Rental Terverifikasi"></i>' : ''}
                                </p>
                            </div>
                            ${data.kronologi ? `
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Kronologi Kejadian</label>
                                <div class="alert alert-warning">
                                    ${data.kronologi}
                                </div>
                            </div>
                            ` : ''}
                            ${data.jumlah_laporan > 1 ? `
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-history me-2"></i>
                                    <strong>Multiple Reports:</strong> Terlapor ini memiliki ${data.jumlah_laporan} laporan.
                                    <a href="/laporan/${data.nik}/timeline" class="alert-link">Lihat Timeline Lengkap</a>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `);

                    // Show/hide buttons based on user role and unlock status
                    @auth
                        @if(Auth::user()->role === 'user')
                            if (data.is_full_access) {
                                $('#unlockDetailBtn').hide();
                                $('#printDetailBtn').removeClass('d-none');
                                $('#downloadPdfBtn').removeClass('d-none');
                            } else {
                                $('#unlockDetailBtn').show();
                                $('#printDetailBtn').addClass('d-none');
                                $('#downloadPdfBtn').addClass('d-none');
                            }
                        @else
                            $('#unlockDetailBtn').hide();
                            $('#printDetailBtn').removeClass('d-none');
                            $('#downloadPdfBtn').removeClass('d-none');
                        @endif
                    @else
                        $('#unlockDetailBtn').hide();
                        $('#printDetailBtn').addClass('d-none');
                        $('#downloadPdfBtn').addClass('d-none');
                    @endauth

                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                } else {
                    alert(response.message || 'Data tidak ditemukan');
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
    };

    // Show full access info
    window.showFullAccess = function() {
        alert('Untuk akses penuh:\n\n1. Daftar sebagai rental (GRATIS)\n2. Beli kredit untuk akses sekali pakai\n\nKlik "Daftar Rental" di menu untuk mendaftar gratis!');
    };

    @auth
    @if(Auth::user()->role === 'user')
    // View detail with unlock option for regular users
    window.viewDetailWithUnlock = function(id, jenisRental) {
        currentDetailId = id;

        $.ajax({
            url: `/detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    currentDetailData = response.data;
                    const data = response.data;
                    let jenisLaporanHtml = '';
                    if (Array.isArray(data.jenis_laporan)) {
                        data.jenis_laporan.forEach(function(jenis) {
                            jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${jenis}</span>`;
                        });
                    } else {
                        jenisLaporanHtml = `<span class="badge bg-warning text-dark">${data.jenis_laporan}</span>`;
                    }

                    $('#detailContent').html(`
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Nama Lengkap</label>
                                <p class="mb-0">${data.nama_lengkap}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">NIK</label>
                                <p class="mb-0">${data.nik}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">No HP</label>
                                <p class="mb-0">${data.no_hp}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Jenis Rental</label>
                                <p class="mb-0">${data.jenis_rental}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Jumlah Laporan</label>
                                <p class="mb-0"><span class="badge bg-danger">${data.jumlah_laporan} Laporan</span></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-primary">Tanggal Kejadian</label>
                                <p class="mb-0">${data.tanggal_kejadian}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Jenis Laporan</label>
                                <div>${jenisLaporanHtml}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Pelapor</label>
                                <p class="mb-0">
                                    ${data.pelapor}
                                    ${data.is_verified ? '<i class="fas fa-check-circle text-primary ms-2" title="Rental Terverifikasi"></i>' : ''}
                                </p>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-eye-slash me-2"></i>
                                    <strong>Data Sensor:</strong> Alamat dan kronologi disembunyikan.
                                    Klik "Buka Detail Lengkap" untuk melihat data lengkap.
                                </div>
                            </div>
                        </div>
                    `);

                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                } else {
                    alert(response.message || 'Data tidak ditemukan');
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
    };

    // Confirm unlock detail
    window.confirmUnlockDetail = function() {
        if (!currentDetailData) return;

        const price = getPriceByRental(currentDetailData.jenis_rental);

        // Update price in modal footer
        $('#unlockPrice').text('Rp ' + price.toLocaleString('id-ID'));

        $('#confirm-name').text(currentDetailData.nama_lengkap);
        $('#confirm-rental').text(currentDetailData.jenis_rental);
        $('#confirm-price').text('Rp ' + price.toLocaleString('id-ID'));

        $('#detailModal').modal('hide');
        const confirmModal = new bootstrap.Modal(document.getElementById('unlockConfirmModal'));
        confirmModal.show();
    };

    // Handle confirm unlock
    $('#confirmUnlockBtn').on('click', function() {
        if (!currentDetailId) return;

        const btn = $(this);
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

        $.ajax({
            url: `/pengguna/buka/${currentDetailId}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#unlockConfirmModal').modal('hide');

                if (response.success) {
                    // Show success message
                    showAlert('success', response.message);

                    // Show full detail
                    showFullDetail(response.data);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                $('#unlockConfirmModal').modal('hide');
                showAlert('danger', 'Terjadi kesalahan saat membuka detail');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    function showFullDetail(data) {
        // Update current data
        currentDetailData = data;

        let jenisLaporanHtml = '';
        if (Array.isArray(data.jenis_laporan)) {
            data.jenis_laporan.forEach(function(jenis) {
                jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${jenis}</span>`;
            });
        } else {
            jenisLaporanHtml = `<span class="badge bg-warning text-dark">${data.jenis_laporan}</span>`;
        }

        let statusPenangananHtml = '';
        if (Array.isArray(data.status_penanganan)) {
            data.status_penanganan.forEach(function(status) {
                statusPenangananHtml += `<span class="badge bg-info text-dark me-2 mb-2">${status}</span>`;
            });
        }

        let fotoPenyewaHtml = '';
        if (Array.isArray(data.foto_penyewa) && data.foto_penyewa.length > 0) {
            data.foto_penyewa.forEach(function(foto) {
                fotoPenyewaHtml += `<img src="/storage/${foto}" class="img-thumbnail me-2 mb-2" style="max-width: 100px; max-height: 100px;">`;
            });
        }

        let fotoKtpSimHtml = '';
        if (Array.isArray(data.foto_ktp_sim) && data.foto_ktp_sim.length > 0) {
            data.foto_ktp_sim.forEach(function(foto) {
                fotoKtpSimHtml += `<img src="/storage/${foto}" class="img-thumbnail me-2 mb-2" style="max-width: 100px; max-height: 100px;">`;
            });
        }

        let buktiHtml = '';
        if (Array.isArray(data.bukti) && data.bukti.length > 0) {
            data.bukti.forEach(function(bukti) {
                const fileName = bukti.split('/').pop();
                const extension = fileName.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                    buktiHtml += `<img src="/storage/${bukti}" class="img-thumbnail me-2 mb-2" style="max-width: 100px; max-height: 100px;">`;
                } else {
                    buktiHtml += `<a href="/storage/${bukti}" target="_blank" class="btn btn-sm btn-outline-primary me-2 mb-2">${fileName}</a>`;
                }
            });
        }

        $('#detailContent').html(`
            <!-- 1. Informasi Penyewa -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Penyewa</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Nama Lengkap</label>
                            <p class="mb-0">${data.nama_lengkap || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">NIK</label>
                            <p class="mb-0">${data.nik || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Jenis Kelamin</label>
                            <p class="mb-0">${data.jenis_kelamin ? (data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">No HP</label>
                            <p class="mb-0">${data.no_hp || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Alamat Lengkap</label>
                            <p class="mb-0">${data.alamat || 'Tidak ada data'}</p>
                        </div>
                        ${fotoPenyewaHtml ? `
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Foto Penyewa</label>
                            <div>${fotoPenyewaHtml}</div>
                        </div>
                        ` : ''}
                        ${fotoKtpSimHtml ? `
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Foto KTP/SIM</label>
                            <div>${fotoKtpSimHtml}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Pelapor -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-building me-2"></i>Informasi Pelapor</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Nama Perusahaan Rental</label>
                            <p class="mb-0">${data.nama_perusahaan_rental || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Nama Penanggung Jawab</label>
                            <p class="mb-0">${data.nama_penanggung_jawab || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">No WhatsApp</label>
                            <p class="mb-0">${data.no_wa_pelapor || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Email</label>
                            <p class="mb-0">${data.email_pelapor || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Alamat Usaha</label>
                            <p class="mb-0">${data.alamat_usaha || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Website Usaha</label>
                            <p class="mb-0">${data.website_usaha || 'Tidak ada data'}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Detail Masalah -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Detail Masalah</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Kategori Rental</label>
                            <p class="mb-0">${data.jenis_rental || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Tanggal Sewa</label>
                            <p class="mb-0">${data.tanggal_sewa || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Tanggal Kejadian</label>
                            <p class="mb-0">${data.tanggal_kejadian || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Jenis Kendaraan/Barang</label>
                            <p class="mb-0">${data.jenis_kendaraan || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Nomor Polisi</label>
                            <p class="mb-0">${data.nomor_polisi || 'Tidak ada data'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Nilai Kerugian</label>
                            <p class="mb-0">${data.nilai_kerugian ? 'Rp ' + parseInt(data.nilai_kerugian).toLocaleString('id-ID') : 'Tidak ada data'}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Jenis Laporan</label>
                            <div>${jenisLaporanHtml || 'Tidak ada data'}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Kronologi Kejadian</label>
                            <div class="alert alert-warning">${data.kronologi || 'Tidak ada data'}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Status Penanganan -->
            ${statusPenangananHtml ? `
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-gavel me-2"></i>Status Penanganan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Status Penanganan</label>
                            <div>${statusPenangananHtml}</div>
                        </div>
                        ${data.status_lainnya ? `
                        <div class="col-12">
                            <label class="form-label fw-medium text-primary">Status Lainnya</label>
                            <p class="mb-0">${data.status_lainnya}</p>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
            ` : ''}

            <!-- 5. Bukti Pendukung -->
            ${buktiHtml ? `
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-paperclip me-2"></i>Bukti Pendukung</h6>
                </div>
                <div class="card-body">
                    <div>${buktiHtml}</div>
                </div>
            </div>
            ` : ''}

            <!-- 6. Informasi Sistem -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Status Validitas</label>
                            <p class="mb-0"><span class="badge bg-success">${data.status_validitas}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Jumlah Laporan (NIK ini)</label>
                            <p class="mb-0"><span class="badge bg-danger">${data.jumlah_laporan} Laporan</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Pelapor</label>
                            <p class="mb-0">
                                ${data.pelapor_role === 'pengusaha_rental' ?
                                    `<a href="/rental/${data.pelapor_id}/profil" class="text-decoration-none fw-bold text-success">
                                        <i class="fas fa-building me-1"></i>${data.pelapor}
                                    </a>` :
                                    data.pelapor
                                }
                                ${data.is_verified ? '<i class="fas fa-check-circle text-primary ms-2" title="Rental Terverifikasi"></i>' : ''}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-primary">Tanggal Dibuat</label>
                            <p class="mb-0">${data.created_at}</p>
                        </div>
                        ${data.jumlah_laporan > 1 ? `
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-history me-2"></i>
                                <strong>Multiple Reports:</strong> Terlapor ini memiliki ${data.jumlah_laporan} laporan.
                                <a href="/laporan/${data.nik}/timeline" class="alert-link">Lihat Timeline Lengkap</a>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `);

        // Show print and PDF buttons
        $('#unlockDetailBtn').hide();
        $('#printDetailBtn').removeClass('d-none');
        $('#downloadPdfBtn').removeClass('d-none');
                <div class="col-12">
                    <label class="form-label fw-medium text-primary">Kronologi Kejadian</label>
                    <div class="alert alert-warning">
                        ${data.kronologi}
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium text-primary">Pelapor</label>
                    <p class="mb-0">
                        ${data.pelapor}
                        ${data.is_verified ? '<i class="fas fa-check-circle text-primary ms-2" title="Rental Terverifikasi"></i>' : ''}
                    </p>
                </div>
                <div class="col-12">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Data Lengkap:</strong> Anda telah membuka akses penuh ke data ini.
                    </div>
                </div>
            </div>
        `);

        // Hide unlock button in modal footer
        $('#unlockDetailBtn').hide();

        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert at top of container
        $('.container').first().prepend(alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    function getPriceByRental(rental) {
        const priceMap = {
            'Rental Mobil': {{ \App\Models\Setting::get('price_rental_mobil', 1500) }},
            'Rental Motor': {{ \App\Models\Setting::get('price_rental_motor', 1500) }},
            'Kamera': {{ \App\Models\Setting::get('price_kamera', 1000) }}
        };
        return priceMap[rental] || {{ \App\Models\Setting::get('price_lainnya', 800) }};
    }
    @endif
    @endauth

    // Print detail function
    window.printDetail = function() {
        if (!currentDetailId) {
            alert('Data tidak tersedia untuk dicetak');
            return;
        }
        window.open(`/cetak-detail/${currentDetailId}`, '_blank');
    };

    // Download PDF function
    window.downloadPDF = function() {
        if (!currentDetailId) {
            alert('Data tidak tersedia untuk diunduh');
            return;
        }
        window.open(`/unduh-pdf/${currentDetailId}`, '_blank');
    };
});
</script>
@endpush
