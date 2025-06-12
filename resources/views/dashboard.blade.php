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
                                    <a href="{{ route('dashboard.blacklist.create') }}" class="btn btn-danger">
                                        <i class="fas fa-plus me-2"></i>
                                        Tambah Laporan
                                    </a>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
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
                            <a href="{{ route('dashboard.blacklist.create') }}" class="btn btn-danger">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Laporan
                            </a>
                            <a href="{{ route('dashboard.blacklist.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-2"></i>
                                Kelola Laporan
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Cari Publik
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-info">
                                <i class="fas fa-cog me-2"></i>
                                Pengaturan Situs
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-green-500 mr-2"></i>
                        Laporan Terbaru
                    </h3>
                    <a href="{{ route('dashboard.blacklist.index') }}" class="mt-2 sm:mt-0 text-sm text-blue-600 hover:text-blue-500 font-medium">
                        Lihat semua
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="block sm:hidden">
                @forelse($recentReports as $report)
                <div class="p-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $report->nama_lengkap }}</h4>
                            <p class="text-sm text-gray-600 mt-1">NIK: {{ $report->nik }}</p>
                            <p class="text-sm text-gray-600">{{ $report->jenis_rental }}</p>
                            <div class="flex items-center mt-2 space-x-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($report->status_validitas === 'Valid') bg-green-100 text-green-800
                                    @elseif($report->status_validitas === 'Pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $report->status_validitas }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-user mr-1"></i>
                        {{ $report->user->name }}
                    </p>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p>Belum ada laporan</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Rental</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentReports as $report)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $report->nama_lengkap }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-mono">{{ $report->nik }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->jenis_rental }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($report->status_validitas === 'Valid') bg-green-100 text-green-800
                                    @elseif($report->status_validitas === 'Pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $report->status_validitas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $report->created_at->format('d/m/Y') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p>Belum ada laporan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

        $('#dashboardSearchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');

        $.ajax({
            url: '{{ route("dashboard.blacklist.search") }}',
            method: 'POST',
            data: {
                search: search,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayDashboardResults(response.data);
                } else {
                    $('#dashboardResultsList').html('<p class="text-gray-500 text-sm">Tidak ada data ditemukan</p>');
                    $('#dashboardSearchResults').removeClass('hidden');
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                alert('Terjadi kesalahan saat mencari data');
            },
            complete: function() {
                $('#dashboardSearchBtn').prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
            }
        });
    }

    function displayDashboardResults(data) {
        let html = '';
        data.forEach(function(item) {
            const statusClass = item.status_validitas === 'Valid' ? 'bg-green-100 text-green-800' :
                               item.status_validitas === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800';

            html += `
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h5 class="font-medium text-gray-900">${item.nama_lengkap}</h5>
                            <p class="text-sm text-gray-600">NIK: ${item.nik}</p>
                            <p class="text-sm text-gray-600">HP: ${item.no_hp}</p>
                            <p class="text-sm text-gray-600">Rental: ${item.jenis_rental}</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${item.status_validitas}
                                </span>
                                <span class="ml-2 text-xs text-gray-500">
                                    ${item.jumlah_laporan} laporan
                                </span>
                            </div>
                        </div>
                        ${item.can_edit ? `
                            <div class="ml-4 space-x-2">
                                <a href="/dashboard/blacklist/${item.id}/edit" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        $('#dashboardResultsList').html(html);
        $('#dashboardSearchResults').removeClass('hidden');
    }
});
</script>
@endpush
