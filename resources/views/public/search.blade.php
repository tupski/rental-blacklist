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
                                            name="search"
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
                            <a href="{{ route('register') }}" class="btn btn-primary">
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
                                <a href="{{ route('topup.index') }}" class="btn btn-success">
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

            html += `
                <div class="border-bottom p-4 ${index === 0 ? 'border-top' : ''}">
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
        $.ajax({
            url: `/detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Show detail in modal or alert
                    showDetailModal(response.data, response.message);
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
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
                                <a href="{{ route('topup.index') }}" class="btn btn-success">
                                    <i class="fas fa-unlock me-2"></i>
                                    Beli Kredit
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary">
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
});
</script>
@endpush
