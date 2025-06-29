@extends('layouts.main')

@section('title', 'Cari Data Blacklist Rental')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Hero Section -->
        <div class="text-center py-5">
            <h1 class="display-4 fw-bold text-dark mb-4">
                <i class="fas fa-shield-alt text-danger me-3"></i>
                Sistem Blacklist Rental Indonesia
            </h1>
            <p class="lead text-muted mb-5">
                Cek data blacklist pelanggan rental sebelum menyewakan barang Anda
            </p>

            <!-- Search Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <input
                                            type="text"
                                            id="searchInput"
                                            name="cari"
                                            placeholder="Masukkan NIK, Nama Lengkap, atau Nomor HP (min. 3 karakter)"
                                            class="form-control form-control-lg"
                                            required
                                            minlength="3"
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-lg w-100"
                                            id="searchBtn"
                                        >
                                            <i class="fas fa-search me-2"></i>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sponsors Section -->
        @if($sponsors && $sponsors->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
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
                            <div class="row g-3 align-items-center justify-content-center">
                                @foreach($sponsors as $sponsor)
                                <div class="col-auto">
                                    <a href="{{ $sponsor->url }}" target="_blank" class="text-decoration-none">
                                        <div class="sponsor-item p-2 rounded border text-center" style="min-width: 120px;">
                                            @if($sponsor->logo)
                                                <img src="{{ Storage::url($sponsor->logo) }}"
                                                     alt="{{ $sponsor->name }}"
                                                     class="img-fluid mb-2"
                                                     style="max-height: 40px; max-width: 100px;">
                                            @endif
                                            <div class="small text-muted">{{ $sponsor->name }}</div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mobile Slider -->
                        <div class="d-md-none">
                            <div id="sponsorCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($sponsors->chunk(2) as $index => $sponsorChunk)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="row g-2 justify-content-center">
                                            @foreach($sponsorChunk as $sponsor)
                                            <div class="col-6">
                                                <a href="{{ $sponsor->url }}" target="_blank" class="text-decoration-none">
                                                    <div class="sponsor-item p-2 rounded border text-center">
                                                        @if($sponsor->logo)
                                                            <img src="{{ Storage::url($sponsor->logo) }}"
                                                                 alt="{{ $sponsor->name }}"
                                                                 class="img-fluid mb-2"
                                                                 style="max-height: 30px; max-width: 80px;">
                                                        @endif
                                                        <div class="small text-muted">{{ $sponsor->name }}</div>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($sponsors->count() > 2)
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

        <!-- Loading -->
        <div id="loading" class="text-center py-4 d-none">
            <div class="d-inline-flex align-items-center px-4 py-2 bg-danger text-white rounded shadow">
                <div class="spinner-border spinner-border-sm me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Mencari data...
            </div>
        </div>

        <!-- Results -->
        <div id="results" class="d-none">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list text-primary me-2"></i>
                        Hasil Pencarian
                    </h3>
                    <p class="text-muted small mb-0 mt-1" id="resultCount"></p>
                </div>

                <div id="resultsList" class="card-body p-0">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- No Results -->
        <div id="noResults" class="text-center py-5 d-none">
            <div class="card border-success">
                <div class="card-body p-5">
                    <i class="fas fa-check-circle text-success display-1 mb-4"></i>
                    <h3 class="text-success mb-3">Data Tidak Ditemukan</h3>
                    <p class="text-muted">
                        Tidak ada data blacklist yang ditemukan untuk pencarian Anda.
                        Ini adalah kabar baik!
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="row g-4 mt-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-4">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Untuk Pengusaha Rental
                        </h3>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Akses data lengkap tanpa sensor</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Tambah laporan blacklist baru</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>Kelola laporan Anda sendiri</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check text-success me-3 mt-1"></i>
                                <span>100% GRATIS untuk pengusaha rental</span>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('daftar') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-4">
                            <i class="fas fa-eye text-info me-2"></i>
                            Untuk Pengguna Umum
                        </h3>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-search text-primary me-3 mt-1"></i>
                                <span>Cari data dengan NIK, nama, atau HP</span>
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
                            @auth
                                <a href="{{ route('isi-saldo.indeks') }}" class="btn btn-success">
                                    <i class="fas fa-coins me-2"></i>
                                    Beli Kredit
                                </a>
                            @else
                                <button class="btn btn-success" onclick="alert('Silakan login terlebih dahulu untuk membeli kredit')">
                                    <i class="fas fa-coins me-2"></i>
                                    Beli Kredit
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
<!-- Modal Bagikan Laporan untuk Halaman Publik -->
<div class="modal fade" id="publicShareModal" tabindex="-1" aria-labelledby="publicShareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publicShareModalLabel">
                    <i class="fas fa-share-alt me-2"></i>
                    Bagikan Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="publicShareForm">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Link berbagi akan memberikan akses ke data sensitif. Pastikan hanya dibagikan kepada pihak yang berwenang.
                    </div>

                    <div class="mb-3">
                        <label for="publicDuration" class="form-label">Durasi Berlaku</label>
                        <select class="form-select" id="publicDuration" name="duration" required>
                            <option value="">Pilih durasi...</option>
                            <option value="1">1 Jam</option>
                            <option value="3">3 Jam</option>
                            <option value="6">6 Jam</option>
                            <option value="12">12 Jam</option>
                            <option value="24">24 Jam</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="publicPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="publicPassword" name="password"
                               placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label for="publicPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="publicPasswordConfirmation" name="password_confirmation"
                               placeholder="Ulangi password" required>
                        <div class="invalid-feedback" id="publicPasswordMismatch" style="display: none;">
                            Password dan konfirmasi password tidak cocok
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="publicOneTimeView" name="one_time_view">
                            <label class="form-check-label" for="publicOneTimeView">
                                <strong>Sekali Lihat</strong> - Link akan kadaluarsa setelah dibuka sekali
                            </label>
                        </div>
                    </div>
                </form>

                <div id="publicShareResult" class="d-none">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Link berbagi berhasil dibuat!
                    </div>
                    <div class="mb-3">
                        <label for="publicShareUrl" class="form-label">Link Berbagi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="publicShareUrl" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyPublicShareUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div id="publicShareInfo" class="small text-muted"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="createPublicShareBtn">
                    <i class="fas fa-share-alt me-2"></i>
                    Buat Link Berbagi
                </button>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection

@push('styles')
<style>
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
    border-left: 4px solid #198754 !important;
}
</style>
@endpush

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
            url: '{{ route('publik.cari') }}',
            method: 'POST',
            data: {
                search: search,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayResults(response.data, response.total);
                    // Auto scroll to results
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $('#results').offset().top - 100
                        }, 800);
                    }, 100);
                } else {
                    showNoResults();
                    // Auto scroll to no results
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $('#noResults').offset().top - 100
                        }, 800);
                    }, 100);
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                alert('Terjadi kesalahan saat mencari data');
            },
            complete: function() {
                $('#loading').addClass('d-none');
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Cari');
            }
        });
    }

    function displayResults(data, total) {
        $('#resultCount').text(`Ditemukan ${total} data blacklist`);

        let html = '';
        data.forEach(function(item, index) {
            const isUnlocked = item.is_verified || false; // Check if user has unlocked this data
            const lockIcon = isUnlocked ? '' : '<i class="fas fa-lock text-warning ms-2" title="Data disensor - beli kredit untuk melihat lengkap"></i>';
            const cardBgClass = isUnlocked ? 'bg-light-success' : ''; // Green background for unlocked data

            html += `
                <div class="border-bottom p-4 ${index === 0 ? 'border-top' : ''} ${cardBgClass}">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="mb-0 me-3">${item.nama_lengkap}${lockIcon}</h5>
                                <span class="badge bg-danger">
                                    ${item.jumlah_laporan} Laporan
                                </span>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-2"></i>
                                        <strong>NIK:</strong> ${item.nik}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-2"></i>
                                        <strong>No HP:</strong> ${item.no_hp}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-car me-2"></i>
                                        <strong>Jenis Rental:</strong> ${item.jenis_rental}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        <strong>Tanggal:</strong> ${item.tanggal_kejadian}
                                    </small>
                                </div>
                            </div>
                            <div class="mb-2">
                                ${item.jenis_laporan.map(laporan => `
                                    <span class="badge bg-warning text-dark me-1 mb-1">
                                        ${formatJenisLaporan(laporan)}
                                    </span>
                                `).join('')}
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-user me-1"></i>
                                Dilaporkan oleh: ${item.pelapor}
                            </div>
                        </div>
                        <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                            <button onclick="showDetail(${item.id})" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-2"></i>
                                Lihat Detail
                            </button>
                            ${!isUnlocked ? `
                                <button onclick="unlockData(${item.id})" class="btn btn-success btn-sm mt-2 w-100">
                                    <i class="fas fa-unlock me-2"></i>
                                    Buka Sensor
                                </button>
                            ` : ''}
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
        history.pushState({}, '', url);
    }

    function formatJenisLaporan(jenis) {
        const mapping = {
            'percobaan_penipuan': 'Percobaan Penipuan',
            'penipuan': 'Penipuan',
            'tidak_mengembalikan_barang': 'Tidak Mengembalikan Barang',
            'identitas_palsu': 'Identitas Palsu',
            'sindikat': 'Sindikat',
            'merusak_barang': 'Merusak Barang'
        };
        return mapping[jenis] || jenis;
    }

    // Global function for detail modal
    window.showDetail = function(id) {
        // Check if this data is unlocked by finding it in current results
        const currentResults = $('#resultsList').find(`[onclick="showDetail(${id})"]`).closest('.border-bottom');
        const isUnlocked = currentResults.hasClass('bg-light-success');

        if (isUnlocked) {
            // Show full detail with print/PDF options
            showFullDetailModal(id);
        } else {
            // Show censored detail with unlock option
            $.ajax({
                url: `/detail/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        showDetailModal(response.data, response.message, id);
                    }
                },
                error: function(xhr) {
                    console.error('Detail error:', xhr);
                    alert('Terjadi kesalahan saat mengambil detail');
                }
            });
        }
    };

    // Global function for unlocking data
    window.unlockData = function(id) {
        @auth
            if (confirm('Apakah Anda ingin membuka sensor data ini? Biaya akan dipotong dari saldo Anda.')) {
                $.ajax({
                    url: `/unlock-data/${id}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data berhasil dibuka!');
                            // Refresh search results
                            performSearch();
                        } else {
                            alert(response.message || 'Gagal membuka data');
                        }
                    },
                    error: function(xhr) {
                        console.error('Unlock error:', xhr);
                        const response = xhr.responseJSON;
                        alert(response?.message || 'Terjadi kesalahan saat membuka data');
                    }
                });
            }
        @else
            alert('Silakan login terlebih dahulu untuk membuka sensor data');
        @endauth
    };

    function showDetailModal(data, message) {
        let modalHtml = `
            <div class="modal fade" id="detailModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Detail Blacklist
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                ${message}
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Nama Lengkap:</strong><br>
                                    ${data.nama_lengkap}
                                </div>
                                <div class="col-md-6">
                                    <strong>NIK:</strong><br>
                                    ${data.nik}
                                </div>
                                <div class="col-md-6">
                                    <strong>No HP:</strong><br>
                                    ${data.no_hp}
                                </div>
                                <div class="col-md-6">
                                    <strong>Jenis Rental:</strong><br>
                                    ${data.jenis_rental}
                                </div>
                                <div class="col-md-6">
                                    <strong>Tanggal Kejadian:</strong><br>
                                    ${data.tanggal_kejadian}
                                </div>
                                <div class="col-md-6">
                                    <strong>Jumlah Laporan:</strong><br>
                                    ${data.jumlah_laporan}
                                </div>
                                <div class="col-12">
                                    <strong>Jenis Laporan:</strong><br>
                                    ${data.jenis_laporan.map(laporan => `
                                        <span class="badge bg-warning text-dark me-1">
                                            ${formatJenisLaporan(laporan)}
                                        </span>
                                    `).join('')}
                                </div>
                                <div class="col-12">
                                    <strong>Dilaporkan oleh:</strong><br>
                                    ${data.pelapor}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            @auth
                                <a href="{{ route('isi-saldo.indeks') }}" class="btn btn-success">
                                    <i class="fas fa-unlock me-2"></i>
                                    Beli Kredit
                                </a>
                            @else
                                <a href="{{ route('daftar') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Daftar Gratis
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#detailModal').remove();

        // Add modal to body and show
        $('body').append(modalHtml);
        $('#detailModal').modal('show');

        // Remove modal from DOM when hidden
        $('#detailModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }

    function showFullDetailModal(id) {
        $.ajax({
            url: `/full-detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let modalHtml = `
                        <div class="modal fade" id="fullDetailModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Detail Lengkap Blacklist
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-success">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data lengkap tanpa sensor - Anda telah membeli akses ke data ini
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Nama Lengkap:</strong><br>
                                                ${response.data.nama_lengkap}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>NIK:</strong><br>
                                                ${response.data.nik}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>No HP:</strong><br>
                                                ${response.data.no_hp}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Alamat:</strong><br>
                                                ${response.data.alamat}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Jenis Kelamin:</strong><br>
                                                ${response.data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Jenis Rental:</strong><br>
                                                ${response.data.jenis_rental}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Tanggal Kejadian:</strong><br>
                                                ${response.data.tanggal_kejadian}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Jumlah Laporan:</strong><br>
                                                ${response.data.jumlah_laporan}
                                            </div>
                                            <div class="col-12">
                                                <strong>Jenis Laporan:</strong><br>
                                                ${response.data.jenis_laporan.map(laporan => \`
                                                    <span class="badge bg-warning text-dark me-1">
                                                        \${formatJenisLaporan(laporan)}
                                                    </span>
                                                \`).join('')}
                                            </div>
                                            <div class="col-12">
                                                <strong>Kronologi:</strong><br>
                                                <div class="border p-3 bg-light rounded">
                                                    ${response.data.kronologi}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <strong>Dilaporkan oleh:</strong><br>
                                                ${response.data.pelapor}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

                                        <!-- Dropdown untuk Print/PDF -->
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-download me-2"></i>
                                                Cetak & Unduh
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="printDetail(${id})">
                                                        <i class="fas fa-print me-2"></i>
                                                        Print
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="downloadPDF(${id})">
                                                        <i class="fas fa-file-pdf me-2"></i>
                                                        Download PDF
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        @auth
                                        <!-- Tombol Bagikan -->
                                        <button type="button" class="btn btn-warning" onclick="showPublicShareModal(${id})">
                                            <i class="fas fa-share-alt me-2"></i>
                                            Bagikan
                                        </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal if any
                    $('#fullDetailModal').remove();

                    // Add modal to body
                    $('body').append(modalHtml);

                    // Show modal
                    $('#fullDetailModal').modal('show');

                    // Remove modal from DOM when hidden
                    $('#fullDetailModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                    });
                } else {
                    alert('Gagal mengambil detail lengkap');
                }
            },
            error: function(xhr) {
                console.error('Full detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail lengkap');
            }
        });
    }

    // Print function
    window.printDetail = function(id) {
        window.open(`/print-detail/${id}`, '_blank');
    };

    // Download PDF function
    window.downloadPDF = function(id) {
        window.open(`/download-pdf/${id}`, '_blank');
    };

    // Global variable to store current share ID
    let currentPublicShareId = null;

    // Show public share modal
    window.showPublicShareModal = function(id) {
        currentPublicShareId = id;

        // Reset form
        $('#publicShareForm')[0].reset();
        $('#publicShareResult').addClass('d-none');
        $('#publicPasswordMismatch').hide();
        $('#publicPasswordConfirmation').removeClass('is-invalid');
        $('#publicShareModal').modal('show');
    };

    // Real-time password validation for public form
    $('#publicPassword, #publicPasswordConfirmation').on('input', function() {
        const password = $('#publicPassword').val();
        const confirmation = $('#publicPasswordConfirmation').val();

        if (confirmation.length > 0) {
            if (password !== confirmation) {
                $('#publicPasswordConfirmation').addClass('is-invalid');
                $('#publicPasswordMismatch').show();
            } else {
                $('#publicPasswordConfirmation').removeClass('is-invalid');
                $('#publicPasswordMismatch').hide();
            }
        }
    });

    // Handle public share form submission
    $('#createPublicShareBtn').on('click', function() {
        if (!currentPublicShareId) {
            alert('Tidak ada data yang dipilih');
            return;
        }

        const form = $('#publicShareForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const password = $('#publicPassword').val();
        const passwordConfirmation = $('#publicPasswordConfirmation').val();

        if (password !== passwordConfirmation) {
            alert('Konfirmasi password tidak cocok');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Membuat Link...');

        $.ajax({
            url: `/dasbor/daftar-hitam/${currentPublicShareId}/bagikan`,
            method: 'POST',
            data: {
                duration: $('#publicDuration').val(),
                password: password,
                password_confirmation: passwordConfirmation,
                one_time_view: $('#publicOneTimeView').is(':checked') ? 1 : 0,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#publicShareUrl').val(response.share_url);
                    $('#publicShareInfo').html(`
                        <i class="fas fa-clock me-1"></i>Berlaku hingga: ${response.expires_at}<br>
                        ${response.one_time_view ? '<i class="fas fa-eye me-1"></i>Sekali lihat: Ya' : '<i class="fas fa-eye me-1"></i>Sekali lihat: Tidak'}
                    `);
                    $('#publicShareResult').removeClass('d-none');
                    btn.html('<i class="fas fa-check me-2"></i>Link Dibuat');
                } else {
                    alert('Gagal membuat link berbagi: ' + (response.message || 'Terjadi kesalahan'));
                }
            },
            error: function(xhr) {
                console.error('Share error:', xhr);
                let errorMessage = 'Terjadi kesalahan saat membuat link';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage += '\n\nDetail error:\n' + errors.join('\n');
                    }
                }
                alert(errorMessage);
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Copy public share URL to clipboard
    window.copyPublicShareUrl = function() {
        const shareUrl = $('#publicShareUrl')[0];
        shareUrl.select();
        shareUrl.setSelectionRange(0, 99999);
        document.execCommand('copy');

        // Show feedback
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    };
});
</script>
@endpush
