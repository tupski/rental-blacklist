@extends('layouts.admin')

@section('title', 'Manajemen Topup')
@section('page-title', 'Manajemen Topup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Topup</li>
@endsection

@push('styles')
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .loading-content {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.75em;
    }
    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .quick-filter.active {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    .invoice-number {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #007bff;
    }
    .table-responsive {
        border-radius: 0.5rem;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .collapse.show {
        border-bottom: 1px solid #dee2e6;
    }
    .form-control-sm {
        font-size: 0.875rem;
    }
    .text-truncate {
        max-width: 200px;
    }
    .table-row-hover:hover {
        background-color: #f8f9fa;
    }
    .empty-state {
        padding: 2rem;
    }
</style>
@endpush

@section('content')
<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2">Memuat data...</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $allTopups->whereIn('status', ['pending', 'pending_confirmation'])->count() }}</h3>
                <p>Menunggu Persetujuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $allTopups->where('status', 'confirmed')->count() }}</h3>
                <p>Disetujui</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $allTopups->where('status', 'rejected')->count() }}</h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp {{ number_format($allTopups->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}</h3>
                <p>Total Disetujui</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" id="dataCount">Daftar Permintaan Topup ({{ $topups->total() }} data)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#filterCollapse">
                        <i class="fas fa-filter"></i> Filter & Pencarian
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="collapse show" id="filterCollapse">
                <div class="card-body border-bottom">
                    <!-- Quick Filters -->
                    <div class="mb-3">
                        <small class="text-muted">Filter Cepat:</small>
                        <div class="btn-group btn-group-sm ml-2" role="group">
                            <button type="button" class="btn btn-outline-warning quick-filter" data-status="pending">
                                <i class="fas fa-clock"></i> Pending
                            </button>
                            <button type="button" class="btn btn-outline-info quick-filter" data-status="pending_confirmation">
                                <i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi
                            </button>
                            <button type="button" class="btn btn-outline-success quick-filter" data-status="confirmed">
                                <i class="fas fa-check"></i> Confirmed
                            </button>
                            <button type="button" class="btn btn-outline-danger quick-filter" data-status="rejected">
                                <i class="fas fa-times"></i> Rejected
                            </button>
                            <button type="button" class="btn btn-outline-secondary quick-filter" data-status="">
                                <i class="fas fa-list"></i> Semua
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="invoice">Nomor Invoice</label>
                                <input type="text" class="form-control form-control-sm" id="invoice" name="invoice"
                                       placeholder="Cari nomor invoice...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user">Nama/Email User</label>
                                <input type="text" class="form-control form-control-sm" id="user" name="user"
                                       placeholder="Cari nama atau email...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control form-control-sm" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="pending_confirmation">Menunggu Konfirmasi</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggal_dari">Tanggal Dari</label>
                                <input type="date" class="form-control form-control-sm" id="tanggal_dari" name="tanggal_dari">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggal_sampai">Tanggal Sampai</label>
                                <input type="date" class="form-control form-control-sm" id="tanggal_sampai" name="tanggal_sampai">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="jumlah_min">Jumlah Min</label>
                                <input type="number" class="form-control form-control-sm" id="jumlah_min" name="jumlah_min"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="jumlah_max">Jumlah Max</label>
                                <input type="number" class="form-control form-control-sm" id="jumlah_max" name="jumlah_max"
                                       placeholder="999999999">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary btn-sm mr-2" id="searchBtn">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="resetBtn">
                                        <i class="fas fa-times"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Filter Row (Hidden by default) -->
                    <div class="row" id="resetFilterRow" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Filter aktif diterapkan.
                                <button type="button" class="btn btn-sm btn-outline-info ml-2" id="resetBtn2">
                                    <i class="fas fa-times"></i> Reset Semua Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table Content -->
            <div id="tableContent">
                @include('admin.topup.partials.table', ['topups' => $topups])
            </div>

            <!-- Pagination -->
            <div class="card-footer" id="paginationContainer">
                @include('admin.topup.partials.pagination', ['topups' => $topups])
            </div>
        </div>
    </div>
</div>



<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Topup</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui permintaan topup ini?</p>
                    <p class="text-muted">Saldo akan ditambahkan ke akun user setelah disetujui.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Topup</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_notes">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes"
                                  rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;

    // Load data function
    function loadData(page = 1) {
        $('#loadingOverlay').removeClass('d-none');

        const formData = {
            invoice: $('#invoice').val(),
            user: $('#user').val(),
            status: $('#status').val(),
            tanggal_dari: $('#tanggal_dari').val(),
            tanggal_sampai: $('#tanggal_sampai').val(),
            jumlah_min: $('#jumlah_min').val(),
            jumlah_max: $('#jumlah_max').val(),
            page: page
        };

        $.ajax({
            url: '{{ route('admin.isi-saldo.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#tableContent').html(response.html);
                    $('#paginationContainer').html(response.pagination_html);
                    updateDataCount(response.pagination.total);
                    updateResetButton();
                    currentPage = page;
                }
            },
            error: function(xhr) {
                console.error('Error loading data:', xhr);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            },
            complete: function() {
                $('#loadingOverlay').addClass('d-none');
            }
        });
    }

    // Update data count
    function updateDataCount(total) {
        $('#dataCount').text('Daftar Permintaan Topup (' + total + ' data)');
    }

    // Update reset button visibility
    function updateResetButton() {
        const hasFilters = $('#invoice').val() || $('#user').val() || $('#status').val() ||
                          $('#tanggal_dari').val() || $('#tanggal_sampai').val() ||
                          $('#jumlah_min').val() || $('#jumlah_max').val();
        if (hasFilters) {
            $('#resetFilterRow').show();
        } else {
            $('#resetFilterRow').hide();
        }
    }

    // Search button click
    $('#searchBtn').on('click', function() {
        loadData(1);
    });

    // Auto-submit on select change
    $('#status, #tanggal_dari, #tanggal_sampai').on('change', function() {
        loadData(1);
    });

    // Enter key on search inputs
    $('#invoice, #user, #jumlah_min, #jumlah_max').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            loadData(1);
        }
    });

    // Reset button
    $('#resetBtn, #resetBtn2').on('click', function() {
        $('#invoice').val('');
        $('#user').val('');
        $('#status').val('');
        $('#tanggal_dari').val('');
        $('#tanggal_sampai').val('');
        $('#jumlah_min').val('');
        $('#jumlah_max').val('');
        loadData(1);
    });

    // Quick filter buttons
    $('.quick-filter').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');

        // Reset other filters
        $('#invoice').val('');
        $('#user').val('');
        $('#tanggal_dari').val('');
        $('#tanggal_sampai').val('');
        $('#jumlah_min').val('');
        $('#jumlah_max').val('');

        // Set status and load
        $('#status').val(status);
        loadData(1);

        // Update button states
        $('.quick-filter').removeClass('active');
        $(this).addClass('active');
    });

    // Initialize
    updateResetButton();
});

// Global functions for modal actions
function approveTopup(topupId) {
    $('#approveForm').attr('action', '/admin/isi-saldo/' + topupId + '/setujui');
    $('#approveModal').modal('show');
}

function rejectTopup(topupId) {
    $('#rejectForm').attr('action', '/admin/isi-saldo/' + topupId + '/tolak');
    $('#rejectModal').modal('show');
}
</script>
@endpush
