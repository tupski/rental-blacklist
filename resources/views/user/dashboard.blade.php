@extends('layouts.main')

@section('title', 'Dashboard User')

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
                                    <i class="fas fa-user-circle text-primary me-3"></i>
                                    Dashboard User
                                </h1>
                                <p class="text-muted mb-1">
                                    Selamat datang, <span class="fw-bold text-primary">{{ Auth::user()->name }}</span>!
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ now()->format('l, d F Y') }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="d-flex flex-column gap-2">
                                    <div class="badge bg-success fs-6 py-2">
                                        <i class="fas fa-wallet me-2"></i>
                                        Saldo: {{ $stats['saldo_tersisa'] }}
                                    </div>
                                    <a href="{{ route('isi-saldo.buat') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>
                                        Topup Saldo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-database text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['total_laporan'] }}</h3>
                            <small class="text-muted">Total Data Blacklist</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-unlock text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['data_dibuka'] }}</h3>
                            <small class="text-muted">Data Dibuka</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-money-bill-wave text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Total Pengeluaran</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-wallet text-info fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['saldo_tersisa'] }}</h3>
                            <small class="text-muted">Saldo Tersisa</small>
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
                        <small class="text-muted">Data ditampilkan dalam bentuk sensor. Klik "Lihat Detail" untuk membuka data lengkap.</small>
                    </div>
                    <div class="card-body">
                        <form id="userSearchForm">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="userSearchInput"
                                    placeholder="Masukkan NIK atau Nama Lengkap (min 3 karakter)"
                                    class="form-control"
                                    minlength="3"
                                >
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    id="userSearchBtn"
                                >
                                    <i class="fas fa-search me-2"></i>
                                    Cari
                                </button>
                            </div>
                        </form>

                        <!-- Search Results -->
                        <div id="userSearchResults" class="mt-4 d-none">
                            <hr>
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-list-ul me-2 text-success"></i>
                                Hasil Pencarian:
                            </h6>
                            <div id="userResultsList">
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
                            Menu Utama
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('isi-saldo.buat') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>
                                Topup Saldo
                            </a>
                            <a href="{{ route('saldo.riwayat') }}" class="btn btn-info">
                                <i class="fas fa-history me-2"></i>
                                Riwayat Transaksi
                            </a>
                            <a href="{{ route('beranda') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Pencarian Publik
                            </a>
                        </div>

                        <!-- Pricing Info -->
                        <div class="alert alert-info mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-1"></i>
                                Harga Detail
                            </h6>
                            <ul class="mb-0 small">
                                <li>Rental Mobil/Motor: <strong>Rp 1.500</strong></li>
                                <li>Rental Kamera: <strong>Rp 1.000</strong></li>
                                <li>Lainnya: <strong>Rp 800</strong></li>
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
                        Data Blacklist Terbaru (Sensor)
                    </h5>
                    <small class="text-muted mt-2 mt-sm-0">
                        Data ditampilkan dalam bentuk sensor untuk privasi
                    </small>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="d-block d-sm-none">
                @forelse($recentReports as $report)
                <div class="card-body border-bottom">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-fill">
                            <h6 class="fw-bold text-dark mb-1">{{ $report['nama_lengkap'] }}</h6>
                            <p class="text-muted small mb-1">NIK: {{ $report['nik'] }}</p>
                            <p class="text-muted small mb-1">HP: {{ $report['no_hp'] }}</p>
                            <p class="text-muted small mb-2">{{ $report['jenis_rental'] }}</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success">{{ $report['status_validitas'] }}</span>
                                <small class="text-muted">{{ $report['jumlah_laporan'] }} laporan</small>
                            </div>
                        </div>
                        <div class="ms-3">
                            <button class="btn btn-sm btn-outline-primary unlock-btn"
                                    data-id="{{ $report['id'] }}"
                                    data-name="{{ $report['nama_lengkap'] }}"
                                    data-rental="{{ $report['jenis_rental'] }}">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data blacklist</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View -->
            <div class="d-none d-sm-block">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nama (Sensor)</th>
                                <th class="border-0">NIK (Sensor)</th>
                                <th class="border-0">HP (Sensor)</th>
                                <th class="border-0">Jenis Rental</th>
                                <th class="border-0">Laporan</th>
                                <th class="border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td class="align-middle">
                                    <div class="fw-bold text-dark">{{ $report['nama_lengkap'] }}</div>
                                </td>
                                <td class="align-middle">
                                    <code class="text-dark">{{ $report['nik'] }}</code>
                                </td>
                                <td class="align-middle">
                                    <span class="text-dark">{{ $report['no_hp'] }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="text-dark">{{ $report['jenis_rental'] }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-success">{{ $report['jumlah_laporan'] }} laporan</span>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-sm btn-outline-primary unlock-btn"
                                            data-id="{{ $report['id'] }}"
                                            data-name="{{ $report['nama_lengkap'] }}"
                                            data-rental="{{ $report['jenis_rental'] }}">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data blacklist</p>
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

<!-- Unlock Confirmation Modal -->
<div class="modal fade" id="unlockModal" tabindex="-1" aria-labelledby="unlockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unlockModalLabel">
                    <i class="fas fa-unlock text-warning me-2"></i>
                    Konfirmasi Buka Detail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong> Tindakan ini akan memotong saldo Anda.
                </div>
                <p>Anda akan membuka detail untuk:</p>
                <ul>
                    <li><strong>Nama:</strong> <span id="unlock-name"></span></li>
                    <li><strong>Jenis Rental:</strong> <span id="unlock-rental"></span></li>
                    <li><strong>Biaya:</strong> <span id="unlock-price" class="text-danger fw-bold"></span></li>
                </ul>
                <p class="text-muted small">
                    Setelah membuka detail, Anda akan melihat data lengkap termasuk NIK, nomor HP, alamat, dan kronologi kejadian.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-warning" id="confirmUnlock">
                    <i class="fas fa-unlock me-2"></i>Ya, Buka Detail
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalTitle">
                    <i class="fas fa-info-circle text-success me-2"></i>
                    Detail Blacklist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Detail content will be loaded here -->
            </div>
            <div class="modal-footer" id="detailModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@include('dashboard.blacklist.partials.detail-modal-content')
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
    let currentUnlockId = null;

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // User search
    $('#userSearchForm').on('submit', function(e) {
        e.preventDefault();
        performUserSearch();
    });

    let currentUserSearchQuery = '';
    let currentUserPage = 1;

    function performUserSearch() {
        const search = $('#userSearchInput').val().trim();

        if (search.length < 3) {
            alert('Pencarian minimal 3 karakter');
            return;
        }

        currentUserSearchQuery = search;
        currentUserPage = 1;
        loadUserSearchResults(true);
    }

    function loadUserSearchResults(isNewSearch = false) {
        $('#userSearchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');

        $.ajax({
            url: '{{ route('pengguna.cari') }}',
            method: 'POST',
            data: {
                search: currentUserSearchQuery,
                page: currentUserPage,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayUserResults(response.data, response.pagination, isNewSearch);
                } else {
                    if (isNewSearch) {
                        $('#userResultsList').html('<p class="text-muted small">Tidak ada data ditemukan</p>');
                        $('#userSearchResults').removeClass('d-none');
                    }
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                alert('Terjadi kesalahan saat mencari data');
            },
            complete: function() {
                $('#userSearchBtn').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Cari');
            }
        });
    }

    function displayUserResults(data, pagination, isNewSearch = false) {
        let html = '';

        if (isNewSearch) {
            html = ''; // Clear previous results for new search
        } else {
            html = $('#userResultsList').html(); // Append to existing results
        }

        data.forEach(function(item) {
            const priceFormatted = 'Rp ' + item.price.toLocaleString('id-ID');
            const unlockStatus = item.is_unlocked ?
                `<div class="d-grid gap-1">
                    <button onclick="showUserDetail(${item.id})" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </button>
                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Dibuka</span>
                </div>` :
                `<div class="d-grid gap-1">
                    <button class="btn btn-sm btn-outline-danger unlock-btn"
                             data-id="${item.id}"
                             data-name="${item.nama_lengkap}"
                             data-rental="${item.jenis_rental}"
                             data-price="${item.price}">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </button>
                    <div class="text-center">
                        <small class="text-danger fw-bold">${priceFormatted}</small>
                        <i class="fas fa-exclamation-circle text-warning ms-1"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Data yang dibuka akan selamanya dapat diakses jika diperlukan tanpa perlu membayar lagi. Sekali buka, akses selamanya!"></i>
                    </div>
                </div>`;

            const cardClass = item.is_unlocked ? 'card mb-3 bg-light-success' : 'card mb-3';

            html += `
                <div class="${cardClass}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-fill">
                                <h6 class="fw-bold text-dark">${item.nama_lengkap}</h6>
                                <p class="text-muted small mb-1">NIK: ${item.nik}</p>
                                <p class="text-muted small mb-1">HP: ${item.no_hp}</p>
                                <p class="text-muted small mb-1">Alamat: ${item.alamat}</p>
                                <p class="text-muted small mb-2">Rental: ${item.jenis_rental}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success">${item.status_validitas}</span>
                                    <small class="text-muted">${item.jumlah_laporan} laporan</small>
                                    <small class="text-muted">Pelapor: ${item.pelapor}</small>
                                </div>
                            </div>
                            <div class="ms-3">
                                ${unlockStatus}
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
                    <button id="loadMoreUserBtn" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                    </button>
                </div>
            `;
        }

        $('#userResultsList').html(html);
        $('#userSearchResults').removeClass('d-none');

        // Reinitialize tooltips after AJAX load
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Handle load more button click
    $(document).on('click', '#loadMoreUserBtn', function() {
        currentUserPage++;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memuat...');
        loadUserSearchResults(false);
    });

    // Handle unlock button click
    $(document).on('click', '.unlock-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const rental = $(this).data('rental');
        const price = $(this).data('price') || getPriceByRental(rental);

        currentUnlockId = id;

        $('#unlock-name').text(name);
        $('#unlock-rental').text(rental);
        $('#unlock-price').text('Rp ' + price.toLocaleString('id-ID'));

        $('#unlockModal').modal('show');
    });

    // Handle confirm unlock
    $('#confirmUnlock').on('click', function() {
        if (!currentUnlockId) return;

        const btn = $(this);
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

        $.ajax({
            url: `/pengguna/buka/${currentUnlockId}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#unlockModal').modal('hide');

                if (response.success) {
                    // Show success message
                    showAlert('success', response.message);

                    // Update balance display
                    $('.badge:contains("Saldo:")').text(`Saldo: Rp ${response.remaining_balance.toLocaleString('id-ID')}`);

                    // Show detail modal
                    showDetailModal(response.data);

                    // Update unlock button
                    $(`.unlock-btn[data-id="${currentUnlockId}"]`).replaceWith(
                        '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Dibuka</span>'
                    );
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                $('#unlockModal').modal('hide');
                showAlert('danger', 'Terjadi kesalahan saat membuka detail');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
                currentUnlockId = null;
            }
        });
    });

    function showDetailModal(data) {
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold">Informasi Personal</h6>
                    <table class="table table-borderless table-sm">
                        <tr><td><strong>Nama:</strong></td><td>${data.nama_lengkap}</td></tr>
                        <tr><td><strong>NIK:</strong></td><td>${data.nik}</td></tr>
                        <tr><td><strong>No. HP:</strong></td><td>${data.no_hp}</td></tr>
                        <tr><td><strong>Alamat:</strong></td><td>${data.alamat}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Informasi Laporan</h6>
                    <table class="table table-borderless table-sm">
                        <tr><td><strong>Jenis Rental:</strong></td><td>${data.jenis_rental}</td></tr>
                        <tr><td><strong>Jenis Laporan:</strong></td><td>${Array.isArray(data.jenis_laporan) ? data.jenis_laporan.join(', ') : data.jenis_laporan}</td></tr>
                        <tr><td><strong>Tanggal Kejadian:</strong></td><td>${data.tanggal_kejadian}</td></tr>
                        <tr><td><strong>Total Laporan:</strong></td><td>${data.jumlah_laporan}</td></tr>
                        <tr><td><strong>Pelapor:</strong></td><td>${data.pelapor}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="fw-bold">Kronologi Kejadian</h6>
                    <div class="alert alert-warning">
                        ${data.kronologi}
                    </div>
                </div>
            </div>
        `;

        $('#detailContent').html(content);
        $('#detailModal').modal('show');
    }

    // Global function for showing user detail
    window.showUserDetail = function(id) {
        $.ajax({
            url: `/detail-lengkap/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showFullDetailModal(response.data, id);
                } else {
                    alert('Gagal mengambil detail lengkap');
                }
            },
            error: function(xhr) {
                console.error('Full detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail lengkap');
            }
        });
    };

    function showFullDetailModal(data, id) {
        // Use the same template as rental owner dashboard
        let template = $('#detailModalTemplate').html();

        // Build foto penyewa HTML
        let fotoPenyewaHtml = '';
        if (data.foto_penyewa && data.foto_penyewa.length > 0) {
            fotoPenyewaHtml = '<div class="row g-3">';
            data.foto_penyewa.forEach(function(file) {
                const fileName = file.split('/').pop();
                const fileUrl = `/storage/${file}`;
                fotoPenyewaHtml += `
                    <div class="col-md-4">
                        <div class="card">
                            <img src="${fileUrl}" class="card-img-top" style="height: 150px; object-fit: cover;"
                                 onclick="showImageModal('${fileUrl}', '${fileName}')">
                            <div class="card-body p-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>
                    </div>`;
            });
            fotoPenyewaHtml += '</div>';
        } else {
            fotoPenyewaHtml = '<p class="text-muted">Tidak ada foto penyewa</p>';
        }

        // Build foto KTP/SIM HTML
        let fotoKtpSimHtml = '';
        if (data.foto_ktp_sim && data.foto_ktp_sim.length > 0) {
            fotoKtpSimHtml = '<div class="row g-3">';
            data.foto_ktp_sim.forEach(function(file) {
                const fileName = file.split('/').pop();
                const fileUrl = `/storage/${file}`;
                fotoKtpSimHtml += `
                    <div class="col-md-4">
                        <div class="card">
                            <img src="${fileUrl}" class="card-img-top" style="height: 150px; object-fit: cover;"
                                 onclick="showImageModal('${fileUrl}', '${fileName}')">
                            <div class="card-body p-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>
                    </div>`;
            });
            fotoKtpSimHtml += '</div>';
        } else {
            fotoKtpSimHtml = '<p class="text-muted">Tidak ada foto KTP/SIM</p>';
        }

        // Build bukti HTML
        let buktiHtml = '';
        if (data.bukti && data.bukti.length > 0) {
            buktiHtml = '<div class="row g-3">';
            data.bukti.forEach(function(file) {
                const fileName = file.split('/').pop();
                const fileUrl = `/storage/${file}`;
                const isImage = /\.(jpg|jpeg|png|gif)$/i.test(fileName);
                const isVideo = /\.(mp4|avi|mov|wmv)$/i.test(fileName);

                buktiHtml += `<div class="col-md-4">`;
                if (isImage) {
                    buktiHtml += `
                        <div class="card">
                            <img src="${fileUrl}" class="card-img-top" style="height: 150px; object-fit: cover;"
                                 onclick="showImageModal('${fileUrl}', '${fileName}')">
                            <div class="card-body p-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>`;
                } else if (isVideo) {
                    buktiHtml += `
                        <div class="card">
                            <video controls class="card-img-top" style="height: 150px;">
                                <source src="${fileUrl}" type="video/mp4">
                                Browser Anda tidak mendukung video.
                            </video>
                            <div class="card-body p-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>`;
                } else {
                    buktiHtml += `
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file fa-3x text-muted mb-2"></i>
                                <p class="small mb-2">${fileName}</p>
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        </div>`;
                }
                buktiHtml += `</div>`;
            });
            buktiHtml += '</div>';
        } else {
            buktiHtml = '<p class="text-muted">Tidak ada bukti</p>';
        }

        // Build jenis laporan HTML
        let jenisLaporanHtml = '';
        if (data.jenis_laporan && data.jenis_laporan.length > 0) {
            data.jenis_laporan.forEach(function(jenis) {
                jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${formatJenisLaporan(jenis)}</span>`;
            });
        }

        // Build status penanganan HTML
        let statusPenangananHtml = '';
        if (data.status_penanganan && data.status_penanganan.length > 0) {
            data.status_penanganan.forEach(function(status) {
                statusPenangananHtml += `<span class="badge bg-info me-2 mb-2">${formatStatusPenanganan(status)}</span>`;
            });
        }

        // Replace placeholders
        const replacements = {
            '{nama_lengkap}': data.nama_lengkap || 'N/A',
            '{nik}': data.nik || 'N/A',
            '{jenis_kelamin_formatted}': data.jenis_kelamin_formatted || 'N/A',
            '{no_hp}': data.no_hp || 'N/A',
            '{alamat}': data.alamat || 'N/A',
            '{foto_penyewa_html}': fotoPenyewaHtml,
            '{foto_ktp_sim_html}': fotoKtpSimHtml,
            '{nama_perusahaan_rental}': data.nama_perusahaan_rental || 'N/A',
            '{nama_penanggung_jawab}': data.nama_penanggung_jawab || 'N/A',
            '{no_wa_pelapor}': data.no_wa_pelapor || 'N/A',
            '{email_pelapor}': data.email_pelapor || 'N/A',
            '{alamat_usaha}': data.alamat_usaha || 'N/A',
            '{website_usaha_link}': data.website_usaha ? `<a href="${data.website_usaha}" target="_blank">${data.website_usaha}</a>` : 'N/A',
            '{jenis_rental}': data.jenis_rental || 'N/A',
            '{tanggal_sewa_formatted}': data.tanggal_sewa_formatted || 'N/A',
            '{tanggal_kejadian_formatted}': data.tanggal_kejadian_formatted || 'N/A',
            '{jenis_kendaraan}': data.jenis_kendaraan || 'N/A',
            '{nomor_polisi}': data.nomor_polisi || 'N/A',
            '{nilai_kerugian_formatted}': data.nilai_kerugian_formatted || 'N/A',
            '{jenis_laporan_html}': jenisLaporanHtml,
            '{kronologi}': data.kronologi || 'N/A',
            '{status_penanganan_html}': statusPenangananHtml,
            '{status_lainnya}': data.status_lainnya || 'N/A',
            '{bukti_html}': buktiHtml,
            '{persetujuan_formatted}': data.persetujuan ? 'Ya' : 'Tidak',
            '{nama_pelapor_ttd}': data.nama_pelapor_ttd || 'N/A',
            '{tanggal_pelaporan_formatted}': data.tanggal_pelaporan_formatted || 'N/A',
            '{tipe_pelapor_formatted}': data.tipe_pelapor === 'rental_owner' ? 'Pemilik Rental' : 'Tamu',
            '{status_validitas_badge}': `<span class="badge ${data.status_validitas === 'Valid' ? 'bg-success' : data.status_validitas === 'Pending' ? 'bg-warning' : 'bg-danger'}">${data.status_validitas}</span>`,
            '{jumlah_laporan}': data.jumlah_laporan || 0,
            '{pelapor}': data.pelapor || 'N/A',
            '{created_at}': data.created_at || 'N/A'
        };

        Object.keys(replacements).forEach(key => {
            template = template.replace(new RegExp(key, 'g'), replacements[key]);
        });

        $('#detailContent').html(template);

        // Update modal title
        $('#detailModalTitle').html('<i class="fas fa-check-circle text-success me-2"></i>Detail Lengkap - Data Terbuka');

        // Update modal footer with print and share buttons
        $('#detailModalFooter').html(`
            <div class="d-flex justify-content-between w-100">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="printDetail(${id})">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF(${id})">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </button>
                </div>
            </div>
        `);

        $('#detailModal').modal('show');
    }

    // Print function
    window.printDetail = function(id) {
        window.open(`/cetak-detail/${id}`, '_blank');
    };

    // Download PDF function
    window.downloadPDF = function(id) {
        window.open(`/unduh-pdf/${id}`, '_blank');
    };

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert at top of container
        $('.container').prepend(alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    function getPriceByRental(rental) {
        const priceMap = {
            'Rental Mobil': {{ \App\Models\Setting::get('price_rental_mobil', 1500) }},
            'Rental Motor': {{ \App\Models\Setting::get('price_rental_motor', 1500) }},
            'Kamera': {{ \App\Models\Setting::get('price_kamera', 1000) }}
        };
        return priceMap[rental] || {{ \App\Models\Setting::get('price_lainnya', 800) }};
    }

    // Helper functions for formatting
    function formatJenisLaporan(jenis) {
        const mapping = {
            'tidak_mengembalikan': 'Tidak Mengembalikan',
            'merusak_barang': 'Merusak Barang',
            'tidak_membayar': 'Tidak Membayar',
            'menyalahgunakan': 'Menyalahgunakan',
            'lainnya': 'Lainnya'
        };
        return mapping[jenis] || jenis;
    }

    function formatStatusPenanganan(status) {
        const mapping = {
            'laporan_polisi': 'Laporan Polisi',
            'mediasi': 'Mediasi',
            'tuntutan_hukum': 'Tuntutan Hukum',
            'blacklist_internal': 'Blacklist Internal',
            'tidak_ada_tindakan': 'Tidak Ada Tindakan'
        };
        return mapping[status] || status;
    }

    // Image modal
    window.showImageModal = function(imageSrc, fileName) {
        $('#modalImage').attr('src', imageSrc);
        $('#downloadImageLink').attr('href', imageSrc);
        $('#imageModalTitle').text(fileName || 'Lihat Gambar');
        $('#imageModal').modal('show');
    };

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert at top of container
        $('.container').first().prepend(alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }
});
</script>
@endpush
