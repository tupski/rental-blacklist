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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="rentalDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-info" onclick="printRentalDetail()">
                    <i class="fas fa-print me-2"></i>
                    Print
                </button>
                <button type="button" class="btn btn-primary" onclick="downloadRentalPDF()">
                    <i class="fas fa-download me-2"></i>
                    Download PDF
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
                                @if(Auth::user()->role === 'pengusaha_rental')
                                <button onclick="showRentalDetail(${item.id})" class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                @endif
                                ${item.can_edit ? `
                                    <a href="/dasbor/daftar-hitam/${item.id}/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                ` : ''}
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
        const content = $('#rentalDetailContent').html();
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Detail Laporan Blacklist - CekPenyewa.com</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @media print {
                            .btn { display: none !important; }
                        }
                        .watermark {
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            font-size: 6rem;
                            color: rgba(218, 53, 68, 0.1);
                            z-index: -1;
                            pointer-events: none;
                        }
                    </style>
                </head>
                <body>
                    <div class="watermark">CekPenyewa.com</div>
                    <div class="container mt-4">
                        <div class="text-center mb-4">
                            <h3>Detail Laporan Blacklist</h3>
                            <p class="text-muted">CekPenyewa.com - ${new Date().toLocaleDateString('id-ID')}</p>
                        </div>
                        ${content}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    };

    // Download PDF function
    window.downloadRentalPDF = function() {
        // Simple implementation - you can enhance this with jsPDF
        printRentalDetail();
    };
    @endif
});
</script>
@endpush
