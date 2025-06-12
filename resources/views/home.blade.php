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
<div class="bg-gradient-to-br from-red-50 via-white to-orange-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-red-600">
                            <i class="fas fa-shield-alt mr-2"></i>
                            {{ $settings['site_name'] }}
                        </h1>
                        <p class="text-sm text-gray-500">{{ $settings['site_tagline'] }}</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 text-sm font-medium transition duration-200">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition duration-200">
                            Daftar Gratis
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Content -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                {{ $settings['hero_title'] }}
            </h2>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                {{ $settings['hero_subtitle'] }}
            </p>

            <!-- Search Form -->
            <div class="max-w-2xl mx-auto mb-12">
                <form id="searchForm" class="flex flex-col sm:flex-row gap-4 p-2 bg-white rounded-2xl shadow-lg border border-gray-200">
                    <div class="flex-1">
                        <input
                            type="text"
                            id="searchInput"
                            name="search"
                            placeholder="Masukkan NIK atau Nama Lengkap (min. 3 karakter)"
                            class="w-full px-6 py-4 border-0 rounded-xl focus:ring-2 focus:ring-red-500 text-lg placeholder-gray-400"
                            required
                            minlength="3"
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-8 py-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 shadow-lg"
                        id="searchBtn"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Cari Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_laporan']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100">
                        <i class="fas fa-user-times text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pelanggan Bermasalah</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_pelanggan_bermasalah']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-store text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rental Terdaftar</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['rental_terdaftar']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Laporan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['laporan_bulan_ini']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="hidden text-center py-8">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-red-500 hover:bg-red-400 transition ease-in-out duration-150 cursor-not-allowed">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mencari data...
            </div>
        </div>

        <!-- Results -->
        <div id="results" class="hidden">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
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
        <div id="noResults" class="hidden text-center py-12">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Data Ditemukan</h3>
                <p class="text-gray-600">Data yang Anda cari tidak ditemukan dalam database blacklist.</p>
                <p class="text-sm text-green-600 mt-2">✓ Pelanggan ini kemungkinan aman untuk disewakan</p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-16">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users text-blue-500 mr-2"></i>
                    Untuk Pengusaha Rental
                </h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                        <span>Daftar gratis dan akses penuh</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                        <span>Lihat data lengkap tanpa sensor</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                        <span>Tambah laporan pelanggan bermasalah</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                        <span>Kelola data laporan Anda</span>
                    </li>
                </ul>
                <div class="mt-6">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-eye text-purple-500 mr-2"></i>
                    Untuk Pengguna Umum
                </h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-search text-blue-500 mr-3 mt-1"></i>
                        <span>Cari data dengan NIK atau nama</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-eye-slash text-orange-500 mr-3 mt-1"></i>
                        <span>Data ditampilkan dengan sensor</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-credit-card text-green-500 mr-3 mt-1"></i>
                        <span>Beli kredit untuk lihat data lengkap</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-shield-alt text-red-500 mr-3 mt-1"></i>
                        <span>Data terverifikasi dan terpercaya</span>
                    </li>
                </ul>
                <div class="mt-6">
                    <button class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-coins mr-2"></i>
                        Beli Kredit
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold text-red-400 mb-4">
                        <i class="fas fa-shield-alt mr-2"></i>
                        {{ $settings['site_name'] }}
                    </h3>
                    <p class="text-gray-300 mb-4">
                        {{ $settings['meta_description'] }}
                    </p>
                    <div class="flex space-x-4">
                        @if($settings['facebook_url'])
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if($settings['twitter_url'])
                        <a href="{{ $settings['twitter_url'] }}" target="_blank" class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if($settings['instagram_url'])
                        <a href="{{ $settings['instagram_url'] }}" target="_blank" class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($settings['whatsapp_number'])
                        <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition duration-200">Cek Blacklist</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Daftar Rental</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Lapor Masalah</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">API Access</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition duration-200">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Kontak</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} RentalGuard. Semua hak dilindungi.</p>
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
        $('#loading').removeClass('hidden');
        $('#results').addClass('hidden');
        $('#noResults').addClass('hidden');
        $('#searchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');

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
                $('#loading').addClass('hidden');
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari Sekarang');
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
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Jenis Laporan:</strong> ${item.jenis_laporan}
                                </div>
                                <div>
                                    <i class="fas fa-calendar mr-2"></i>
                                    <strong>Tanggal Kejadian:</strong> ${item.tanggal_kejadian}
                                </div>
                                <div>
                                    <i class="fas fa-user mr-2"></i>
                                    <strong>Pelapor:</strong> ${item.pelapor}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button onclick="viewDetail(${item.id})" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200">
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
