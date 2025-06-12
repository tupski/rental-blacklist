@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-tachometer-alt text-blue-600 mr-3"></i>
                            Dashboard
                        </h1>
                        <p class="text-gray-600">
                            Selamat datang kembali, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>!
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('dashboard.blacklist.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition duration-200 transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Laporan
                            </a>
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Cari Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 shadow-lg">
                        <i class="fas fa-list text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_laporan'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Semua laporan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Laporan Saya</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['laporan_saya'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Yang saya buat</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-red-500 to-red-600 shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Laporan Valid</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['laporan_valid'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Terverifikasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['laporan_pending'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Menunggu validasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2">
                <!-- Search Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-search text-blue-500 mr-2"></i>
                        Cari Data Blacklist
                    </h3>
                    <form id="dashboardSearchForm" class="flex flex-col sm:flex-row gap-4">
                        <input
                            type="text"
                            id="dashboardSearchInput"
                            placeholder="Masukkan NIK atau Nama Lengkap"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            minlength="3"
                        >
                        <button
                            type="submit"
                            class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-105"
                            id="dashboardSearchBtn"
                        >
                            <i class="fas fa-search mr-2"></i>
                            Cari
                        </button>
                    </form>

                    <!-- Search Results -->
                    <div id="dashboardSearchResults" class="mt-4 hidden">
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-medium text-gray-900 mb-3">
                                <i class="fas fa-list-ul mr-2 text-green-500"></i>
                                Hasil Pencarian:
                            </h4>
                            <div id="dashboardResultsList" class="space-y-3">
                                <!-- Results will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('dashboard.blacklist.create') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-center font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition duration-200 transform hover:scale-105 shadow-md">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Laporan
                        </a>
                        <a href="{{ route('dashboard.blacklist.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white text-center font-semibold rounded-lg hover:from-gray-600 hover:to-gray-700 transition duration-200 transform hover:scale-105 shadow-md">
                            <i class="fas fa-list mr-2"></i>
                            Kelola Laporan
                        </a>
                        <a href="{{ route('home') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200 transform hover:scale-105 shadow-md">
                            <i class="fas fa-search mr-2"></i>
                            Cari Publik
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-center font-semibold rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-200 transform hover:scale-105 shadow-md">
                            <i class="fas fa-cog mr-2"></i>
                            Pengaturan Situs
                        </a>
                    </div>

                    <!-- Tips -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                        <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Tips
                        </h4>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Laporkan pelanggan bermasalah segera</li>
                            <li>• Upload bukti untuk validasi</li>
                            <li>• Cek blacklist sebelum menyewakan</li>
                        </ul>
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
            data: { search: search },
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
