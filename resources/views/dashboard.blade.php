@extends('layouts.main')

@section('title', 'Dashboard')

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
                                    <i class="fas fa-tachometer-alt text-primary me-3"></i>
                                    Dashboard
                                </h1>
                                <p class="text-muted mb-1">
                                    Selamat datang kembali, <span class="fw-bold text-primary">{{ Auth::user()->name }}</span>!
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ now()->format('l, d F Y') }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="d-flex flex-column flex-lg-row gap-2">
                                    <a href="{{ route('dasbor.daftar-hitam.buat') }}" class="btn btn-danger">
                                        <i class="fas fa-plus me-2"></i>
                                        Tambah Laporan
                                    </a>
                                    <a href="{{ route('beranda') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-search me-2"></i>
                                        Cari Publik
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm hover-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-list text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Laporan</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_laporan'] }}</h3>
                            <small class="text-muted">Semua laporan</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm hover-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-user-edit text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Laporan Saya</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['laporan_saya'] }}</h3>
                            <small class="text-muted">Yang saya buat</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm hover-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fas fa-check-circle text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Laporan Valid</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['laporan_valid'] }}</h3>
                            <small class="text-muted">Terverifikasi</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm hover-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['laporan_pending'] }}</h3>
                            <small class="text-muted">Menunggu validasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <!-- Search Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-search text-primary me-2"></i>
                            Cari Data Blacklist
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="dashboardSearchForm">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="dashboardSearchInput"
                                    placeholder="Masukkan NIK atau Nama Lengkap"
                                    class="form-control"
                                    minlength="3"
                                    value="{{ $searchQuery ?? '' }}"
                                >
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    id="dashboardSearchBtn"
                                >
                                    <i class="fas fa-search me-2"></i>
                                    Cari
                                </button>
                            </div>
                        </form>

                        <!-- Search Results -->
                        <div id="dashboardSearchResults" class="mt-4 d-none">
                            <hr>
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-list-ul me-2 text-success"></i>
                                Hasil Pencarian:
                            </h6>
                            <div id="dashboardResultsList">
                                <!-- Results will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('dasbor.daftar-hitam.buat') }}" class="btn btn-danger">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Laporan
                            </a>
                            <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-2"></i>
                                Kelola Laporan
                            </a>
                            <a href="{{ route('beranda') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Cari Publik
                            </a>
                            <a href="{{ route('api.dokumentasi') }}" class="btn btn-info">
                                <i class="fas fa-code me-2"></i>
                                API Documentation
                            </a>
                        </div>

                        <!-- Tips -->
                        <div class="alert alert-warning mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-1"></i>
                                Tips
                            </h6>
                            <ul class="mb-0 small">
                                <li>Laporkan pelanggan bermasalah segera</li>
                                <li>Upload bukti untuk validasi</li>
                                <li>Cek blacklist sebelum menyewakan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-success me-2"></i>
                        Laporan Terbaru
                    </h5>
                    <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="mt-2 mt-sm-0 text-decoration-none">
                        Lihat semua
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="d-block d-sm-none">
                @forelse($recentReports as $report)
                <div class="card-body border-bottom">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-fill">
                            <h6 class="fw-bold text-dark mb-1">{{ $report->nama_lengkap }}</h6>
                            <p class="text-muted small mb-1">NIK: {{ $report->nik }}</p>
                            <p class="text-muted small mb-2">{{ $report->jenis_rental }}</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge
                                    @if($report->status_validitas === 'Valid') bg-success
                                    @elseif($report->status_validitas === 'Pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ $report->status_validitas }}
                                </span>
                                <small class="text-muted">{{ $report->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-user me-1"></i>
                        {{ $report->user->name }}
                    </p>
                    @if(Auth::user()->role === 'pengusaha_rental')
                    <div class="mt-2">
                        <button onclick="showRentalDetail({{ $report->id }})" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                    @endif
                </div>
                @empty
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                    <p class="text-muted">Belum ada laporan</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View -->
            <div class="d-none d-sm-block">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nama</th>
                                <th class="border-0">NIK</th>
                                <th class="border-0">Jenis Rental</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Pelapor</th>
                                <th class="border-0">Tanggal</th>
                                @if(Auth::user()->role === 'pengusaha_rental')
                                <th class="border-0">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td class="align-middle">
                                    <div class="fw-bold text-dark">{{ $report->nama_lengkap }}</div>
                                </td>
                                <td class="align-middle">
                                    <code class="text-dark">{{ $report->nik }}</code>
                                </td>
                                <td class="align-middle">
                                    <span class="text-dark">{{ $report->jenis_rental }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge
                                        @if($report->status_validitas === 'Valid') bg-success
                                        @elseif($report->status_validitas === 'Pending') bg-warning
                                        @else bg-danger @endif">
                                        {{ $report->status_validitas }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="text-dark">{{ $report->user->name }}</span>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">{{ $report->created_at->format('d/m/Y') }}</small>
                                </td>
                                @if(Auth::user()->role === 'pengusaha_rental')
                                <td class="align-middle">
                                    <button onclick="showRentalDetail({{ $report->id }})" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada laporan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role === 'pengusaha_rental')
<!-- Rental Detail Modal -->
<div class="modal fade" id="rentalDetailModal" tabindex="-1" aria-labelledby="rentalDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rentalDetailModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    Detail Laporan Blacklist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="rentalDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Tutup
                </button>

                <!-- Dropdown untuk Print/PDF -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-2"></i>
                        Cetak & Unduh
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" onclick="printRentalDetail()">
                                <i class="fas fa-print me-2"></i>
                                Print
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="downloadRentalPDF()">
                                <i class="fas fa-file-pdf me-2"></i>
                                Download PDF
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tombol Bagikan -->
                <button type="button" class="btn btn-warning" onclick="showShareModal()">
                    <i class="fas fa-share-alt me-2"></i>
                    Bagikan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bagikan Laporan -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">
                    <i class="fas fa-share-alt me-2"></i>
                    Bagikan Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="shareForm">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Link berbagi akan memberikan akses ke data sensitif. Pastikan hanya dibagikan kepada pihak yang berwenang.
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Durasi Berlaku</label>
                        <select class="form-select" id="duration" name="duration" required>
                            <option value="">Pilih durasi...</option>
                            <option value="1">1 Jam</option>
                            <option value="3">3 Jam</option>
                            <option value="6">6 Jam</option>
                            <option value="12">12 Jam</option>
                            <option value="24">24 Jam</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password" required>
                        <div class="invalid-feedback" id="passwordMismatch" style="display: none;">
                            Password dan konfirmasi password tidak cocok
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="one_time_view" name="one_time_view">
                            <label class="form-check-label" for="one_time_view">
                                <strong>Sekali Lihat</strong> - Link akan kadaluarsa setelah dibuka sekali
                            </label>
                        </div>
                    </div>
                </form>

                <div id="shareResult" class="d-none">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Link berbagi berhasil dibuat!
                    </div>
                    <div class="mb-3">
                        <label for="shareUrl" class="form-label">Link Berbagi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareUrl" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div id="shareInfo" class="small text-muted"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="createShareBtn">
                    <i class="fas fa-share-alt me-2"></i>
                    Buat Link Berbagi
                </button>
            </div>
        </div>
    </div>
</div>
@else
<!-- User Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="fas fa-file-alt me-2"></i>
                    Detail Laporan Blacklist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-warning" onclick="showUserShareModal()" id="userShareBtn" style="display: none;">
                    <i class="fas fa-share-alt me-2"></i>
                    Bagikan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bagikan Laporan untuk User -->
<div class="modal fade" id="userShareModal" tabindex="-1" aria-labelledby="userShareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userShareModalLabel">
                    <i class="fas fa-share-alt me-2"></i>
                    Bagikan Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userShareForm">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Link berbagi akan memberikan akses ke data sensitif. Pastikan hanya dibagikan kepada pihak yang berwenang.
                    </div>

                    <div class="mb-3">
                        <label for="userDuration" class="form-label">Durasi Berlaku</label>
                        <select class="form-select" id="userDuration" name="duration" required>
                            <option value="">Pilih durasi...</option>
                            <option value="1">1 Jam</option>
                            <option value="3">3 Jam</option>
                            <option value="6">6 Jam</option>
                            <option value="12">12 Jam</option>
                            <option value="24">24 Jam</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="userPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="userPassword" name="password"
                               placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label for="userPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="userPasswordConfirmation" name="password_confirmation"
                               placeholder="Ulangi password" required>
                        <div class="invalid-feedback" id="userPasswordMismatch" style="display: none;">
                            Password dan konfirmasi password tidak cocok
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="userOneTimeView" name="one_time_view">
                            <label class="form-check-label" for="userOneTimeView">
                                <strong>Sekali Lihat</strong> - Link akan kadaluarsa setelah dibuka sekali
                            </label>
                        </div>
                    </div>
                </form>

                <div id="userShareResult" class="d-none">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Link berbagi berhasil dibuat!
                    </div>
                    <div class="mb-3">
                        <label for="userShareUrl" class="form-label">Link Berbagi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="userShareUrl" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyUserShareUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div id="userShareInfo" class="small text-muted"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="createUserShareBtn">
                    <i class="fas fa-share-alt me-2"></i>
                    Buat Link Berbagi
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Check if there's a search parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('cari');

    if (searchParam) {
        // Auto-perform search from URL
        $('#dashboardSearchInput').val(searchParam);
        performDashboardSearch();
    }

    // Dashboard search
    $('#dashboardSearchForm').on('submit', function(e) {
        e.preventDefault();
        performDashboardSearch();
    });

    // Auto-update URL parameter saat mengetik
    $('#dashboardSearchInput').on('input', function() {
        const search = $(this).val().trim();
        if (search.length >= 3) {
            updateURL(search);
        } else if (search.length === 0) {
            updateURL('');
        }
    });

    let currentDashboardSearchQuery = '';
    let currentDashboardPage = 1;

    function performDashboardSearch() {
        const search = $('#dashboardSearchInput').val().trim();

        if (search.length < 3) {
            alert('Pencarian minimal 3 karakter');
            return;
        }

        currentDashboardSearchQuery = search;
        currentDashboardPage = 1;

        // Update URL with search parameter
        updateURL(search);

        loadDashboardSearchResults(true);
    }

    function updateURL(search) {
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('cari', search);
        } else {
            url.searchParams.delete('cari');
        }
        window.history.pushState({}, '', url);
    }

    function loadDashboardSearchResults(isNewSearch = false) {
        $('#dashboardSearchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');

        $.ajax({
            url: '{{ route('dasbor.daftar-hitam.cari') }}',
            method: 'POST',
            data: {
                search: currentDashboardSearchQuery,
                page: currentDashboardPage,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayDashboardResults(response.data, response.pagination, isNewSearch);
                } else {
                    if (isNewSearch) {
                        $('#dashboardResultsList').html('<p class="text-muted small">Tidak ada data ditemukan</p>');
                        $('#dashboardSearchResults').removeClass('d-none');
                    }
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                alert('Terjadi kesalahan saat mencari data');
            },
            complete: function() {
                $('#dashboardSearchBtn').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Cari');
            }
        });
    }

    function displayDashboardResults(data, pagination, isNewSearch = false) {
        let html = '';

        if (isNewSearch) {
            html = ''; // Clear previous results for new search
        } else {
            html = $('#dashboardResultsList').html(); // Append to existing results
        }

        data.forEach(function(item) {
            const statusClass = item.status_validitas === 'Valid' ? 'bg-success' :
                               item.status_validitas === 'Pending' ? 'bg-warning' :
                               'bg-danger';

            html += `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-fill">
                                <h6 class="fw-bold text-dark">${item.nama_lengkap}</h6>
                                <p class="text-muted small mb-1">NIK: ${item.nik}</p>
                                <p class="text-muted small mb-1">HP: ${item.no_hp}</p>
                                <p class="text-muted small mb-1">Alamat: ${item.alamat}</p>
                                <p class="text-muted small mb-2">Rental: ${item.jenis_rental}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge ${statusClass}">
                                        ${item.status_validitas}
                                    </span>
                                    <small class="text-muted">
                                        ${item.jumlah_laporan} laporan
                                    </small>
                                    <small class="text-muted">
                                        Pelapor: ${item.pelapor}
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="d-flex flex-column gap-1">
                                    <a href="/detail-laporan/${item.id}" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-alt"></i> Detail Lengkap
                                    </a>
                                    @if(Auth::user()->role === 'pengusaha_rental')
                                    <button onclick="showRentalDetail(${item.id})" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    @else
                                    <button onclick="showDetail(${item.id})" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    @endif
                                    ${item.can_edit ? `
                                        <a href="/dasbor/daftar-hitam/${item.id}/edit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        // Add load more button if there are more results
        if (pagination.has_more) {
            html += `
                <div class="text-center mt-3">
                    <button id="loadMoreDashboardBtn" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                    </button>
                </div>
            `;
        }

        $('#dashboardResultsList').html(html);
        $('#dashboardSearchResults').removeClass('d-none');
    }

    // Handle load more button click
    $(document).on('click', '#loadMoreDashboardBtn', function() {
        currentDashboardPage++;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memuat...');
        loadDashboardSearchResults(false);
    });

    @if(Auth::user()->role === 'pengusaha_rental')
    // Rental detail function
    window.showRentalDetail = function(id) {
        currentRentalDetailId = id; // Store current ID for print/PDF

        // Show loading
        $('#rentalDetailModal').modal('show');
        $('#rentalDetailContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat data...</p></div>');

        // Fetch detail data
        $.ajax({
            url: `/rental/blacklist/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    displayRentalDetailModal(response.data);
                } else {
                    $('#rentalDetailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            },
            error: function() {
                $('#rentalDetailContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>');
            }
        });
    };

    function displayRentalDetailModal(data) {
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Data Pribadi</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Nama Lengkap:</strong></td><td>${data.nama_lengkap}</td></tr>
                        <tr><td><strong>NIK:</strong></td><td>${data.nik}</td></tr>
                        <tr><td><strong>No. HP:</strong></td><td>${data.no_hp}</td></tr>
                        <tr><td><strong>Alamat:</strong></td><td>${data.alamat || '-'}</td></tr>
                        <tr><td><strong>Jenis Kelamin:</strong></td><td>${data.jenis_kelamin || '-'}</td></tr>
                        <tr><td><strong>Tanggal Lahir:</strong></td><td>${data.tanggal_lahir || '-'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Data Laporan</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Jenis Rental:</strong></td><td>${data.jenis_rental}</td></tr>
                        <tr><td><strong>Kronologi:</strong></td><td>${data.kronologi || '-'}</td></tr>
                        <tr><td><strong>Kerugian:</strong></td><td>${data.kerugian_finansial ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.kerugian_finansial) : '-'}</td></tr>
                        <tr><td><strong>Status:</strong></td><td><span class="badge ${data.status_validitas === 'Valid' ? 'bg-success' : data.status_validitas === 'Pending' ? 'bg-warning' : 'bg-danger'}">${data.status_validitas}</span></td></tr>
                        <tr><td><strong>Pelapor:</strong></td><td>${data.user ? data.user.name : '-'}</td></tr>
                        <tr><td><strong>Tanggal Laporan:</strong></td><td>${new Date(data.created_at).toLocaleDateString('id-ID')}</td></tr>
                    </table>
                </div>
            </div>

            ${data.bukti_pendukung && data.bukti_pendukung.length > 0 ? `
                <div class="mt-4">
                    <h6 class="text-info mb-3"><i class="fas fa-paperclip me-2"></i>Bukti Pendukung</h6>
                    <div class="row">
                        ${data.bukti_pendukung.map(file => `
                            <div class="col-md-3 mb-2">
                                <div class="card">
                                    <div class="card-body text-center p-2">
                                        ${file.includes('.jpg') || file.includes('.jpeg') || file.includes('.png') || file.includes('.gif') ?
                                            `<img src="/storage/${file}" class="img-fluid" style="max-height: 100px;">` :
                                            `<i class="fas fa-file fa-3x text-muted"></i>`
                                        }
                                        <p class="small mt-1 mb-0">${file.split('/').pop()}</p>
                                        <a href="/storage/${file}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        `;

        $('#rentalDetailContent').html(content);
    }

    // Print function
    window.printRentalDetail = function() {
        if (currentRentalDetailId) {
            window.open(`/rental/cetak-detail/${currentRentalDetailId}`, '_blank');
        } else {
            alert('Tidak ada data yang dipilih');
        }
    };

    // Download PDF function
    window.downloadRentalPDF = function() {
        if (currentRentalDetailId) {
            window.open(`/rental/unduh-pdf/${currentRentalDetailId}`, '_blank');
        } else {
            alert('Tidak ada data yang dipilih');
        }
    };

    // Variable to store current detail ID
    let currentRentalDetailId = null;

    // Show share modal
    window.showShareModal = function() {
        if (!currentRentalDetailId) {
            alert('Tidak ada data yang dipilih');
            return;
        }

        // Reset form
        $('#shareForm')[0].reset();
        $('#shareResult').addClass('d-none');
        $('#passwordMismatch').hide();
        $('#password_confirmation').removeClass('is-invalid');
        $('#shareModal').modal('show');
    };

    // Real-time password validation
    $('#password, #password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();

        if (confirmation.length > 0) {
            if (password !== confirmation) {
                $('#password_confirmation').addClass('is-invalid');
                $('#passwordMismatch').show();
            } else {
                $('#password_confirmation').removeClass('is-invalid');
                $('#passwordMismatch').hide();
            }
        }
    });

    // Handle share form submission
    $('#createShareBtn').on('click', function() {
        if (!currentRentalDetailId) {
            alert('Tidak ada data yang dipilih');
            return;
        }

        const form = $('#shareForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const password = $('#password').val();
        const passwordConfirmation = $('#password_confirmation').val();

        if (password !== passwordConfirmation) {
            alert('Konfirmasi password tidak cocok');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Membuat Link...');

        $.ajax({
            url: `/rental/bagikan/${currentRentalDetailId}`,
            method: 'POST',
            data: {
                duration: $('#duration').val(),
                password: password,
                password_confirmation: passwordConfirmation,
                one_time_view: $('#one_time_view').is(':checked') ? 1 : 0,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#shareUrl').val(response.share_url);
                    $('#shareInfo').html(`
                        <i class="fas fa-clock me-1"></i>Berlaku hingga: ${response.expires_at}<br>
                        ${response.one_time_view ? '<i class="fas fa-eye me-1"></i>Sekali lihat: Ya' : '<i class="fas fa-eye me-1"></i>Sekali lihat: Tidak'}
                    `);
                    $('#shareResult').removeClass('d-none');
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

    // Copy share URL to clipboard
    window.copyShareUrl = function() {
        const shareUrl = $('#shareUrl')[0];
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
    @endif

    @if(Auth::user()->role !== 'pengusaha_rental')
    // Variable to store current user detail ID
    let currentUserDetailId = null;

    // User detail function (for regular users)
    window.showDetail = function(id) {
        currentUserDetailId = id; // Store current ID for sharing

        // Show loading
        $('#detailModal').modal('show');
        $('#detailContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat data...</p></div>');

        // Fetch detail data
        $.ajax({
            url: `/detail/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    displayDetailModal(response.data);
                } else {
                    $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            },
            error: function() {
                $('#detailContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>');
            }
        });
    };

    function displayDetailModal(data) {
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Data Penyewa</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Nama Lengkap:</strong></td><td>${data.nama_lengkap}</td></tr>
                        <tr><td><strong>NIK:</strong></td><td>${data.nik}</td></tr>
                        <tr><td><strong>No. HP:</strong></td><td>${data.no_hp}</td></tr>
                        <tr><td><strong>Alamat:</strong></td><td>${data.alamat || '-'}</td></tr>
                        <tr><td><strong>Jenis Kelamin:</strong></td><td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Data Laporan</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Jenis Rental:</strong></td><td>${data.jenis_rental}</td></tr>
                        <tr><td><strong>Tanggal Kejadian:</strong></td><td>${data.tanggal_kejadian}</td></tr>
                        <tr><td><strong>Kronologi:</strong></td><td>${data.kronologi || '-'}</td></tr>
                        <tr><td><strong>Status:</strong></td><td><span class="badge ${data.is_verified ? 'bg-success' : 'bg-warning'}">${data.is_verified ? 'Terverifikasi' : 'Belum Terverifikasi'}</span></td></tr>
                        <tr><td><strong>Jumlah Laporan:</strong></td><td>${data.jumlah_laporan} laporan</td></tr>
                        <tr><td><strong>Pelapor:</strong></td><td>${data.pelapor}</td></tr>
                    </table>
                </div>
            </div>

            ${!data.is_full_access ? `
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Data Disensor:</strong> ${data.message}
                </div>
            ` : ''}
        `;

        $('#detailContent').html(content);

        // Show/hide share button based on access level
        if (data.is_full_access) {
            $('#userShareBtn').show();
        } else {
            $('#userShareBtn').hide();
        }
    }

    // Show user share modal
    window.showUserShareModal = function() {
        if (!currentUserDetailId) {
            alert('Tidak ada data yang dipilih');
            return;
        }

        // Reset form
        $('#userShareForm')[0].reset();
        $('#userShareResult').addClass('d-none');
        $('#userPasswordMismatch').hide();
        $('#userPasswordConfirmation').removeClass('is-invalid');
        $('#userShareModal').modal('show');
    };

    // Real-time password validation for user form
    $('#userPassword, #userPasswordConfirmation').on('input', function() {
        const password = $('#userPassword').val();
        const confirmation = $('#userPasswordConfirmation').val();

        if (confirmation.length > 0) {
            if (password !== confirmation) {
                $('#userPasswordConfirmation').addClass('is-invalid');
                $('#userPasswordMismatch').show();
            } else {
                $('#userPasswordConfirmation').removeClass('is-invalid');
                $('#userPasswordMismatch').hide();
            }
        }
    });

    // Handle user share form submission
    $('#createUserShareBtn').on('click', function() {
        if (!currentUserDetailId) {
            alert('Tidak ada data yang dipilih');
            return;
        }

        const form = $('#userShareForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const password = $('#userPassword').val();
        const passwordConfirmation = $('#userPasswordConfirmation').val();

        if (password !== passwordConfirmation) {
            alert('Konfirmasi password tidak cocok');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Membuat Link...');

        $.ajax({
            url: `/pengguna/bagikan/${currentUserDetailId}`,
            method: 'POST',
            data: {
                duration: $('#userDuration').val(),
                password: password,
                password_confirmation: passwordConfirmation,
                one_time_view: $('#userOneTimeView').is(':checked') ? 1 : 0,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#userShareUrl').val(response.share_url);
                    $('#userShareInfo').html(`
                        <i class="fas fa-clock me-1"></i>Berlaku hingga: ${response.expires_at}<br>
                        ${response.one_time_view ? '<i class="fas fa-eye me-1"></i>Sekali lihat: Ya' : '<i class="fas fa-eye me-1"></i>Sekali lihat: Tidak'}
                    `);
                    $('#userShareResult').removeClass('d-none');
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

    // Copy user share URL to clipboard
    window.copyUserShareUrl = function() {
        const shareUrl = $('#userShareUrl')[0];
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
    @endif
});
</script>
@endpush
