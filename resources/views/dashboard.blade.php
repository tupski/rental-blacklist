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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Dashboard search
    $('#dashboardSearchForm').on('submit', function(e) {
        e.preventDefault();
        performDashboardSearch();
    });

    function performDashboardSearch() {
        const search = $('#dashboardSearchInput').val().trim();

        if (search.length < 3) {
            alert('Pencarian minimal 3 karakter');
            return;
        }

        $('#dashboardSearchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');

        $.ajax({
            url: '{{ route('dasbor.daftar-hitam.cari') }}',
            method: 'POST',
            data: {
                search: search,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayDashboardResults(response.data);
                } else {
                    $('#dashboardResultsList').html('<p class="text-muted small">Tidak ada data ditemukan</p>');
                    $('#dashboardSearchResults').removeClass('d-none');
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

    function displayDashboardResults(data) {
        let html = '';
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
                                <p class="text-muted small mb-2">Rental: ${item.jenis_rental}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge ${statusClass}">
                                        ${item.status_validitas}
                                    </span>
                                    <small class="text-muted">
                                        ${item.jumlah_laporan} laporan
                                    </small>
                                </div>
                            </div>
                            ${item.can_edit ? `
                                <div class="ms-3">
                                    <a href="/dashboard/blacklist/${item.id}/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        $('#dashboardResultsList').html(html);
        $('#dashboardSearchResults').removeClass('d-none');
    }
});
</script>
@endpush
