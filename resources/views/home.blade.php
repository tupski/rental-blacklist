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
<!-- Hero Section -->
<div class="bg-gradient-to-br from-danger-subtle to-warning-subtle min-vh-100">
    <!-- Hero Content -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="display-3 fw-bold text-dark mb-4">
                    {{ $settings['hero_title'] }}
                </h1>
                <p class="lead fs-4 text-muted mb-5">
                    {{ $settings['hero_subtitle'] }}
                </p>

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
                                            name="search"
                                            class="form-control border-0 shadow-none"
                                            placeholder="Masukkan NIK atau Nama Lengkap (min. 3 karakter)"
                                            required
                                            minlength="3"
                                        >
                                        <button
                                            type="submit"
                                            class="btn btn-danger px-4"
                                            id="searchBtn"
                                        >
                                            <i class="fas fa-search me-2"></i>
                                            Cari Sekarang
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
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

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h4 class="fw-bold text-danger mb-3">
                        <i class="fas fa-shield-alt me-2"></i>
                        {{ $settings['site_name'] }}
                    </h4>
                    <p class="text-light mb-4">
                        {{ $settings['meta_description'] }}
                    </p>
                    <div class="d-flex gap-3">
                        @if($settings['facebook_url'])
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="text-light">
                            <i class="fab fa-facebook-f fs-5"></i>
                        </a>
                        @endif
                        @if($settings['twitter_url'])
                        <a href="{{ $settings['twitter_url'] }}" target="_blank" class="text-light">
                            <i class="fab fa-twitter fs-5"></i>
                        </a>
                        @endif
                        @if($settings['instagram_url'])
                        <a href="{{ $settings['instagram_url'] }}" target="_blank" class="text-light">
                            <i class="fab fa-instagram fs-5"></i>
                        </a>
                        @endif
                        @if($settings['whatsapp_number'])
                        <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="text-light">
                            <i class="fab fa-whatsapp fs-5"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Cek Blacklist</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Daftar Rental</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Lapor Masalah</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">API Access</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3">Bantuan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kebijakan Privasi</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $settings['site_name'] }}. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentSearch = '';

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
    const searchParam = urlParams.get('search');
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
            url: '{{ route("public.search") }}',
            method: 'POST',
            data: {
                search: search,
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
                <div class="border-bottom p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="fw-bold mb-0 me-3">${item.nama_lengkap}</h5>
                                <span class="badge bg-danger">
                                    ${item.jumlah_laporan} Laporan
                                </span>
                            </div>
                            <div class="row g-3 text-muted small">
                                <div class="col-md-6">
                                    <i class="fas fa-id-card me-2"></i>
                                    <strong>NIK:</strong> ${item.nik}
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-phone me-2"></i>
                                    <strong>No HP:</strong> ${item.no_hp}
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-car me-2"></i>
                                    <strong>Jenis Rental:</strong> ${item.jenis_rental}
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Jenis Laporan:</strong> ${item.jenis_laporan}
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-calendar me-2"></i>
                                    <strong>Tanggal Kejadian:</strong> ${item.tanggal_kejadian}
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-user me-2"></i>
                                    <strong>Pelapor:</strong> ${item.pelapor}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button onclick="viewDetail(${item.id})" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#resultsList').html(html);
        $('#results').removeClass('d-none');
    }

    function showNoResults() {
        $('#noResults').removeClass('d-none');
    }

    function updateURL(search) {
        const url = new URL(window.location);
        url.searchParams.set('search', search);
        window.history.replaceState({}, '', url);
    }

    // View detail function
    window.viewDetail = function(id) {
        $.ajax({
            url: `/detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
    };
});
</script>
@endpush
