@extends('layouts.main')

@section('title', 'Cari Data Blacklist Rental')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            <i class="fas fa-shield-alt text-red-600 mr-3"></i>
            Sistem Blacklist Rental Indonesia
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Cek data blacklist pelanggan rental sebelum menyewakan barang Anda
        </p>
        
        <!-- Search Form -->
        <div class="max-w-2xl mx-auto">
            <form id="searchForm" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="searchInput" 
                        name="search" 
                        placeholder="Masukkan NIK atau Nama Lengkap (min. 3 karakter)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-lg"
                        required
                        minlength="3"
                    >
                </div>
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                    id="searchBtn"
                >
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center py-8 hidden">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-red-500 transition ease-in-out duration-150">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mencari data...
        </div>
    </div>

    <!-- Results -->
    <div id="results" class="hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2"></i>
                    Hasil Pencarian
                </h3>
                <p class="text-sm text-gray-600 mt-1" id="resultCount"></p>
            </div>
            
            <div id="resultsList" class="divide-y divide-gray-200">
                <!-- Results will be populated here -->
            </div>
        </div>
    </div>

    <!-- No Results -->
    <div id="noResults" class="text-center py-12 hidden">
        <div class="bg-green-50 border border-green-200 rounded-lg p-8">
            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-green-800 mb-2">Data Tidak Ditemukan</h3>
            <p class="text-green-700">
                Tidak ada data blacklist yang ditemukan untuk pencarian Anda. 
                Ini adalah kabar baik!
            </p>
        </div>
    </div>

    <!-- Info Section -->
    <div class="mt-16 grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Untuk Pengusaha Rental
            </h3>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                    <span>Akses data lengkap tanpa sensor</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                    <span>Tambah laporan blacklist baru</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                    <span>Kelola laporan Anda sendiri</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                    <span>100% GRATIS untuk pengusaha rental</span>
                </li>
            </ul>
            <div class="mt-6">
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-eye text-purple-500 mr-2"></i>
                Untuk Pengguna Umum
            </h3>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-search text-blue-500 mr-2 mt-1"></i>
                    <span>Cari data dengan NIK atau nama</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-eye-slash text-orange-500 mr-2 mt-1"></i>
                    <span>Data ditampilkan dengan sensor</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-credit-card text-green-500 mr-2 mt-1"></i>
                    <span>Beli kredit untuk lihat data lengkap</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-shield-alt text-red-500 mr-2 mt-1"></i>
                    <span>Data terverifikasi dan terpercaya</span>
                </li>
            </ul>
            <div class="mt-6">
                <button class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-200" onclick="alert('Fitur pembayaran akan segera hadir!')">
                    <i class="fas fa-coins mr-2"></i>
                    Beli Kredit
                </button>
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
        $('#loading').removeClass('hidden');
        $('#results').addClass('hidden');
        $('#noResults').addClass('hidden');
        $('#searchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');

        // Perform AJAX search
        $.ajax({
            url: '{{ route("public.search") }}',
            method: 'POST',
            data: { search: search },
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
                $('#loading').addClass('hidden');
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
            }
        });
    }

    function displayResults(data, total) {
        $('#resultCount').text(`Ditemukan ${total} data blacklist`);
        
        let html = '';
        data.forEach(function(item) {
            html += `
                <div class="p-6 hover:bg-gray-50 transition duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">${item.nama_lengkap}</h4>
                                <span class="ml-3 px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    ${item.jumlah_laporan} Laporan
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <i class="fas fa-id-card mr-2"></i>
                                    <strong>NIK:</strong> ${item.nik}
                                </div>
                                <div>
                                    <i class="fas fa-phone mr-2"></i>
                                    <strong>No HP:</strong> ${item.no_hp}
                                </div>
                                <div>
                                    <i class="fas fa-car mr-2"></i>
                                    <strong>Jenis Rental:</strong> ${item.jenis_rental}
                                </div>
                                <div>
                                    <i class="fas fa-calendar mr-2"></i>
                                    <strong>Tanggal:</strong> ${item.tanggal_kejadian}
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="flex flex-wrap gap-2">
                                    ${item.jenis_laporan.map(laporan => `
                                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                            ${formatJenisLaporan(laporan)}
                                        </span>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                Dilaporkan oleh: ${item.pelapor}
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button onclick="showDetail(${item.id})" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#resultsList').html(html);
        $('#results').removeClass('hidden');
    }

    function showNoResults() {
        $('#noResults').removeClass('hidden');
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
