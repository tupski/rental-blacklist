@extends('layouts.main')

@section('title', 'Kelola Laporan Blacklist')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold text-dark">
                <i class="fas fa-list text-danger me-3"></i>
                Kelola Laporan Blacklist
            </h1>
            <p class="text-muted">Kelola semua laporan blacklist rental</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('dasbor.daftar-hitam.buat') }}" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i>
                Tambah Laporan
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter text-primary me-2"></i>
                Filter & Pencarian
            </h5>
        </div>
        <div class="card-body">
            <form id="filterForm">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-medium">Pencarian</label>
                        <input
                            type="text"
                            id="searchFilter"
                            name="cari"
                            placeholder="NIK atau Nama"
                            class="form-control"
                        >
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-medium">Jenis Rental</label>
                        <select id="jenisRentalFilter" name="jenis_rental" class="form-select">
                            <option value="">Semua</option>
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Kamera">Kamera</option>
                            <option value="Alat Elektronik">Alat Elektronik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-medium">Status</label>
                        <select id="statusFilter" name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Valid">Valid</option>
                            <option value="Pending">Pending</option>
                            <option value="Invalid">Invalid</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted">Memuat data...</span>
    </div>

    <!-- Results -->
    <div id="results">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 p-0">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="my-reports-tab" data-bs-toggle="tab" data-bs-target="#my-reports" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Laporan Saya
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="all-reports-tab" data-bs-toggle="tab" data-bs-target="#all-reports" type="button" role="tab">
                            <i class="fas fa-list me-2"></i>Semua Laporan
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="reportTabsContent">
                <!-- Tab Laporan Saya -->
                <div class="tab-pane fade show active" id="my-reports" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Jenis Rental</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="myReportsTableBody">
                                @forelse($myReports as $blacklist)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $blacklist->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $blacklist->no_hp }}</small>
                                    </td>
                                    <td>{{ $blacklist->nik }}</td>
                                    <td>{{ $blacklist->jenis_rental }}</td>
                                    <td>
                                        <span class="badge
                                            @if($blacklist->status_validitas === 'Valid') bg-success
                                            @elseif($blacklist->status_validitas === 'Pending') bg-warning
                                            @else bg-danger @endif">
                                            {{ $blacklist->status_validitas }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $blacklist->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button onclick="showDetail({{ $blacklist->id }})" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('dasbor.daftar-hitam.edit', $blacklist->id) }}" class="btn btn-outline-success btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteBlacklist({{ $blacklist->id }})" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Anda belum memiliki laporan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for My Reports -->
                    @if($myReports->hasPages())
                    <div class="card-footer bg-light">
                        {{ $myReports->links() }}
                    </div>
                    @endif
                </div>

                <!-- Tab Semua Laporan -->
                <div class="tab-pane fade" id="all-reports" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Jenis Rental</th>
                                    <th>Status</th>
                                    <th>Pelapor</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="allReportsTableBody">
                                @forelse($allReports as $blacklist)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $blacklist->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $blacklist->no_hp }}</small>
                                    </td>
                                    <td>{{ $blacklist->nik }}</td>
                                    <td>{{ $blacklist->jenis_rental }}</td>
                                    <td>
                                        <span class="badge
                                            @if($blacklist->status_validitas === 'Valid') bg-success
                                            @elseif($blacklist->status_validitas === 'Pending') bg-warning
                                            @else bg-danger @endif">
                                            {{ $blacklist->status_validitas }}
                                        </span>
                                    </td>
                                    <td>{{ $blacklist->user->name }}</td>
                                    <td>
                                        <small class="text-muted">{{ $blacklist->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button onclick="showDetail({{ $blacklist->id }})" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($blacklist->user_id === Auth::id())
                                                <a href="{{ route('dasbor.daftar-hitam.edit', $blacklist->id) }}" class="btn btn-outline-success btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="deleteBlacklist({{ $blacklist->id }})" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Belum ada data laporan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for All Reports -->
                    @if($allReports->hasPages())
                    <div class="card-footer bg-light">
                        {{ $allReports->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Laporan Blacklist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Tutup
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="downloadPdfBtn">
                            <i class="fas fa-file-pdf me-2"></i>Simpan PDF
                        </button>
                        <button type="button" class="btn btn-warning" id="shareReportBtn">
                            <i class="fas fa-share-alt me-2"></i>Bagikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-share-alt me-2"></i>Bagikan Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Link ini hanya dapat diakses sekali dan akan kedaluwarsa dalam 24 jam.
                </div>
                <form id="shareForm">
                    <div class="mb-3">
                        <label for="sharePassword" class="form-label">Password untuk Akses</label>
                        <input type="password" class="form-control" id="sharePassword" required
                               placeholder="Masukkan password untuk mengakses laporan">
                        <div class="form-text">Password ini diperlukan untuk melihat laporan</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link me-2"></i>Buat Link
                        </button>
                    </div>
                </form>
                <div id="shareResult" class="d-none mt-3">
                    <div class="alert alert-success">
                        <strong>Link berhasil dibuat!</strong>
                        <div class="mt-2">
                            <input type="text" class="form-control" id="shareUrl" readonly>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyShareUrl()">
                                    <i class="fas fa-copy me-1"></i>Salin Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.blacklist.partials.detail-modal-content')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadBlacklists();
    });

    // Auto-filter on input change
    $('#searchFilter, #jenisRentalFilter, #statusFilter').on('change input', function() {
        clearTimeout(window.filterTimeout);
        window.filterTimeout = setTimeout(function() {
            loadBlacklists();
        }, 500);
    });

    function loadBlacklists() {
        $('#loading').removeClass('d-none');

        const formData = {
            search: $('#searchFilter').val(),
            jenis_rental: $('#jenisRentalFilter').val(),
            status: $('#statusFilter').val()
        };

        $.ajax({
            url: '{{ route('dasbor.daftar-hitam.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                console.log('Response received:', response);
                if (response.success) {
                    updateMyReportsTable(response.my_reports);
                    updateAllReportsTable(response.all_reports);
                    updatePagination(response.pagination);
                } else {
                    // Jika tidak ada response yang valid, reload halaman
                    location.reload();
                }
            },
            error: function(xhr) {
                console.error('Load error:', xhr);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            },
            complete: function() {
                $('#loading').addClass('d-none');
            }
        });
    }

    function updateMyReportsTable(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(item) {
                const statusClass = item.status_validitas === 'Valid' ? 'bg-success' :
                                   item.status_validitas === 'Pending' ? 'bg-warning' :
                                   'bg-danger';

                html += `
                    <tr>
                        <td>
                            <div class="fw-medium">${item.nama_lengkap}</div>
                            <small class="text-muted">${item.no_hp}</small>
                        </td>
                        <td>${item.nik}</td>
                        <td>${item.jenis_rental}</td>
                        <td>
                            <span class="badge ${statusClass}">
                                ${item.status_validitas}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">${new Date(item.created_at).toLocaleDateString('id-ID')}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button onclick="showDetail(${item.id})" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="/dasbor/daftar-hitam/${item.id}/edit" class="btn btn-outline-success btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteBlacklist(${item.id})" class="btn btn-outline-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Anda belum memiliki laporan
                    </td>
                </tr>
            `;
        }

        $('#myReportsTableBody').html(html);
    }

    function updateAllReportsTable(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(item) {
                const statusClass = item.status_validitas === 'Valid' ? 'bg-success' :
                                   item.status_validitas === 'Pending' ? 'bg-warning' :
                                   'bg-danger';

                const canEdit = item.user_id === {{ Auth::id() }};

                html += `
                    <tr>
                        <td>
                            <div class="fw-medium">${item.nama_lengkap}</div>
                            <small class="text-muted">${item.no_hp}</small>
                        </td>
                        <td>${item.nik}</td>
                        <td>${item.jenis_rental}</td>
                        <td>
                            <span class="badge ${statusClass}">
                                ${item.status_validitas}
                            </span>
                        </td>
                        <td>${item.user.name}</td>
                        <td>
                            <small class="text-muted">${new Date(item.created_at).toLocaleDateString('id-ID')}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button onclick="showDetail(${item.id})" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${canEdit ? `
                                    <a href="/dasbor/daftar-hitam/${item.id}/edit" class="btn btn-outline-success btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteBlacklist(${item.id})" class="btn btn-outline-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Belum ada data laporan
                    </td>
                </tr>
            `;
        }

        $('#allReportsTableBody').html(html);
    }

    function updatePagination(pagination) {
        // Update pagination for My Reports
        if (pagination.my_reports) {
            updateTabPagination('my-reports', pagination.my_reports);
        }

        // Update pagination for All Reports
        if (pagination.all_reports) {
            updateTabPagination('all-reports', pagination.all_reports);
        }
    }

    function updateTabPagination(tabId, paginationData) {
        const container = $(`#${tabId} .card-footer`);

        if (paginationData.last_page > 1) {
            let paginationHtml = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm justify-content-center mb-0">';

            // Previous button
            if (paginationData.current_page > 1) {
                paginationHtml += `<li class="page-item">
                    <a class="page-link" href="#" onclick="loadPage('${tabId}', ${paginationData.current_page - 1})">Previous</a>
                </li>`;
            }

            // Page numbers
            for (let i = 1; i <= paginationData.last_page; i++) {
                const activeClass = i === paginationData.current_page ? 'active' : '';
                paginationHtml += `<li class="page-item ${activeClass}">
                    <a class="page-link" href="#" onclick="loadPage('${tabId}', ${i})">${i}</a>
                </li>`;
            }

            // Next button
            if (paginationData.current_page < paginationData.last_page) {
                paginationHtml += `<li class="page-item">
                    <a class="page-link" href="#" onclick="loadPage('${tabId}', ${paginationData.current_page + 1})">Next</a>
                </li>`;
            }

            paginationHtml += '</ul></nav>';

            if (container.length === 0) {
                $(`#${tabId}`).append('<div class="card-footer bg-light">' + paginationHtml + '</div>');
            } else {
                container.html(paginationHtml);
            }
        } else {
            container.remove();
        }
    }

    window.loadPage = function(tabId, page) {
        const pageParam = tabId === 'my-reports' ? 'my_page' : 'all_page';
        const formData = {
            search: $('#searchFilter').val(),
            jenis_rental: $('#jenisRentalFilter').val(),
            status: $('#statusFilter').val(),
            [pageParam]: page
        };

        $('#loading').removeClass('d-none');

        $.ajax({
            url: '{{ route('dasbor.daftar-hitam.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    if (tabId === 'my-reports') {
                        updateMyReportsTable(response.my_reports);
                        updateTabPagination('my-reports', response.pagination.my_reports);
                    } else {
                        updateAllReportsTable(response.all_reports);
                        updateTabPagination('all-reports', response.pagination.all_reports);
                    }
                }
            },
            error: function(xhr) {
                console.error('Pagination error:', xhr);
                alert('Terjadi kesalahan saat memuat halaman');
            },
            complete: function() {
                $('#loading').addClass('d-none');
            }
        });
    };

    let currentReportId = null;

    // Global functions
    window.showDetail = function(id) {
        currentReportId = id;

        // Show modal first
        $('#detailModal').modal('show');
        $('#detailContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat data...</p></div>');

        $.ajax({
            url: `/dasbor/daftar-hitam/${id}`,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    displayDetailModal(data);
                } else {
                    $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                $('#detailContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat detail</div>');
            }
        });
    };

    function displayDetailModal(data) {
        // Get template
        let template = $('#detailModalTemplate').html();

        // Helper functions
        function formatJenisLaporan(jenis) {
            const mapping = {
                'Tidak Mengembalikan': 'Tidak Mengembalikan',
                'Merusak Barang': 'Merusak Barang',
                'Tidak Bayar': 'Tidak Bayar',
                'Kabur': 'Kabur',
                'Lainnya': 'Lainnya'
            };
            return mapping[jenis] || jenis;
        }

        function formatStatusPenanganan(status) {
            const mapping = {
                'Lapor Polisi': 'Lapor Polisi',
                'Mediasi': 'Mediasi',
                'Blacklist': 'Blacklist',
                'Lainnya': 'Lainnya'
            };
            return mapping[status] || status;
        }

        // Format jenis laporan
        let jenisLaporanHtml = '';
        if (data.jenis_laporan && data.jenis_laporan.length > 0) {
            data.jenis_laporan.forEach(function(jenis) {
                jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${formatJenisLaporan(jenis)}</span>`;
            });
        } else {
            jenisLaporanHtml = 'Tidak ada data';
        }

        // Format status penanganan
        let statusPenangananHtml = '';
        if (data.status_penanganan && data.status_penanganan.length > 0) {
            data.status_penanganan.forEach(function(status) {
                statusPenangananHtml += `<span class="badge bg-info me-2 mb-2">${formatStatusPenanganan(status)}</span>`;
            });
        } else {
            statusPenangananHtml = 'Tidak ada data';
        }

        // Format foto penyewa
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

        // Format foto KTP/SIM
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

        // Format bukti files
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

        // Foto Penyewa
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

        // Foto KTP/SIM
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

        // Build complete modal content
        let template = $('#detailModalTemplate').html();

        // Replace placeholders
        const replacements = {
            '{nama_lengkap}': data.nama_lengkap || 'N/A',
            '{nik}': data.nik || 'N/A',
            '{jenis_kelamin_formatted}': data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
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
    }

    window.closeDetailModal = function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
        if (modal) modal.hide();
    };

    window.deleteBlacklist = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
            $.ajax({
                url: `/dasbor/daftar-hitam/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        loadBlacklists();
                    }
                },
                error: function(xhr) {
                    console.error('Delete error:', xhr);
                    alert('Terjadi kesalahan saat menghapus data');
                }
            });
        }
    };

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
});
</script>
@endpush
