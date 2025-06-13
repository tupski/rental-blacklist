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
            <a href="{{ route('dashboard.blacklist.create') }}" class="btn btn-danger">
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
                            name="search"
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
            <div class="card-header bg-light border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-database me-2 text-primary"></i>
                    Data Laporan
                </h5>
            </div>

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
                    <tbody id="blacklistTableBody">
                        @forelse($blacklists as $blacklist)
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
                                        <a href="{{ route('dashboard.blacklist.edit', $blacklist->id) }}" class="btn btn-outline-success btn-sm" title="Edit">
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

            <!-- Pagination -->
            @if($blacklists->hasPages())
            <div class="card-footer bg-light">
                {{ $blacklists->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
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
            url: '{{ route("dashboard.blacklist.index") }}',
            method: 'GET',
            data: formData,
            success: function(response) {
                console.log('Response received:', response);
                if (response.success) {
                    updateTable(response.data);
                    updatePagination(response.pagination);
                } else {
                    // Jika tidak ada response.html, reload halaman
                    location.reload();
                }
            },
            error: function(xhr) {
                console.error('Load error:', xhr);
                // Reload halaman jika ada error
                location.reload();
            },
            complete: function() {
                $('#loading').addClass('d-none');
            }
        });
    }

    function updateTable(data) {
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
                                    <a href="/dashboard/blacklist/${item.id}/edit" class="btn btn-outline-success btn-sm" title="Edit">
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
                        Tidak ada data ditemukan
                    </td>
                </tr>
            `;
        }

        $('#blacklistTableBody').html(html);
    }

    // Global functions
    window.showDetail = function(id) {
        $.ajax({
            url: `/dashboard/blacklist/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    let jenisLaporanHtml = '';
                    data.jenis_laporan.forEach(function(jenis) {
                        jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${formatJenisLaporan(jenis)}</span>`;
                    });

                    let buktiHtml = '';
                    if (data.bukti && data.bukti.length > 0) {
                        data.bukti.forEach(function(file) {
                            buktiHtml += `<a href="/storage/${file}" target="_blank" class="text-blue-600 hover:text-blue-800 mr-3">${file.split('/').pop()}</a>`;
                        });
                    } else {
                        buktiHtml = '<span class="text-gray-500">Tidak ada bukti</span>';
                    }

                    $('#detailContent').html(`
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Lengkap</label>
                                <p class="mb-0">${data.nama_lengkap}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">NIK</label>
                                <p class="mb-0">${data.nik}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jenis Kelamin</label>
                                <p class="mb-0">${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">No HP</label>
                                <p class="mb-0">${data.no_hp}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jenis Rental</label>
                                <p class="mb-0">${data.jenis_rental}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Status</label>
                                <p class="mb-0"><span class="badge ${data.status_validitas === 'Valid' ? 'bg-success' : data.status_validitas === 'Pending' ? 'bg-warning' : 'bg-danger'}">${data.status_validitas}</span></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Alamat</label>
                                <p class="mb-0">${data.alamat}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Jenis Laporan</label>
                                <div>${jenisLaporanHtml}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Kronologi</label>
                                <p class="mb-0">${data.kronologi}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tanggal Kejadian</label>
                                <p class="mb-0">${new Date(data.tanggal_kejadian).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Pelapor</label>
                                <p class="mb-0">${data.user.name}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Bukti</label>
                                <div>${buktiHtml}</div>
                            </div>
                        </div>
                    `);
                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
    };

    window.closeDetailModal = function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
        if (modal) modal.hide();
    };

    window.deleteBlacklist = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
            $.ajax({
                url: `/dashboard/blacklist/${id}`,
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
