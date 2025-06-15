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
                        Tidak ada data ditemukan
                    </td>
                </tr>
            `;
        }

        $('#blacklistTableBody').html(html);
    }

    let currentReportId = null;

    // Global functions
    window.showDetail = function(id) {
        currentReportId = id;

        $.ajax({
            url: `/dasbor/daftar-hitam/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    displayDetailModal(data);
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat memuat detail');
            }
        });
    };

    function displayDetailModal(data) {
        let jenisLaporanHtml = '';
        if (data.jenis_laporan && data.jenis_laporan.length > 0) {
            data.jenis_laporan.forEach(function(jenis) {
                jenisLaporanHtml += `<span class="badge bg-warning text-dark me-2 mb-2">${formatJenisLaporan(jenis)}</span>`;
            });
        }

        let statusPenangananHtml = '';
        if (data.status_penanganan && data.status_penanganan.length > 0) {
            data.status_penanganan.forEach(function(status) {
                statusPenangananHtml += `<span class="badge bg-info me-2 mb-2">${formatStatusPenanganan(status)}</span>`;
            });
        }

        // Bukti files
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
        $('#detailModal').modal('show');
    }

    // PDF Download
    $(document).on('click', '#downloadPdfBtn', function() {
        if (currentReportId) {
            window.open(`/dasbor/daftar-hitam/${currentReportId}/pdf`, '_blank');
        }
    });

    // Share functionality
    $(document).on('click', '#shareReportBtn', function() {
        $('#shareModal').modal('show');
        $('#shareResult').addClass('d-none');
        $('#shareForm')[0].reset();
    });

    $('#shareForm').on('submit', function(e) {
        e.preventDefault();

        if (!currentReportId) return;

        const password = $('#sharePassword').val();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Membuat...');

        $.ajax({
            url: `/dasbor/daftar-hitam/${currentReportId}/share`,
            method: 'POST',
            data: {
                password: password,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#shareUrl').val(response.share_url);
                    $('#shareResult').removeClass('d-none');
                }
            },
            error: function(xhr) {
                console.error('Share error:', xhr);
                alert('Terjadi kesalahan saat membuat link');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Copy share URL
    window.copyShareUrl = function() {
        const shareUrl = $('#shareUrl')[0];
        shareUrl.select();
        shareUrl.setSelectionRange(0, 99999);
        document.execCommand('copy');

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Tersalin!';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    };

    // Image modal
    window.showImageModal = function(imageSrc, fileName) {
        $('#modalImage').attr('src', imageSrc);
        $('#downloadImageLink').attr('href', imageSrc);
        $('#imageModalTitle').text(fileName || 'Lihat Gambar');
        $('#imageModal').modal('show');
    };

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
