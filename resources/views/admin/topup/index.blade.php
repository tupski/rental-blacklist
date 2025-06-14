@extends('layouts.admin')

@section('title', 'Manajemen Topup')
@section('page-title', 'Manajemen Topup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Topup</li>
@endsection

@push('styles')
<style>
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
</style>
@endpush

@section('content')
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
                <h3 class="card-title">Daftar Permintaan Topup</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#filterCollapse">
                        <i class="fas fa-filter"></i> Filter & Pencarian
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="collapse {{ request()->hasAny(['status', 'invoice', 'user', 'tanggal_dari', 'tanggal_sampai', 'jumlah_min', 'jumlah_max']) ? 'show' : '' }}" id="filterCollapse">
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.isi-saldo.indeks') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="invoice">Nomor Invoice</label>
                                    <input type="text" class="form-control form-control-sm" id="invoice" name="invoice"
                                           value="{{ request('invoice') }}" placeholder="Cari nomor invoice...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user">Nama/Email User</label>
                                    <input type="text" class="form-control form-control-sm" id="user" name="user"
                                           value="{{ request('user') }}" placeholder="Cari nama atau email...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control form-control-sm" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="pending_confirmation" {{ request('status') == 'pending_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tanggal_dari">Tanggal Dari</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_dari" name="tanggal_dari"
                                           value="{{ request('tanggal_dari') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tanggal_sampai">Tanggal Sampai</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_sampai" name="tanggal_sampai"
                                           value="{{ request('tanggal_sampai') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="jumlah_min">Jumlah Min</label>
                                    <input type="number" class="form-control form-control-sm" id="jumlah_min" name="jumlah_min"
                                           value="{{ request('jumlah_min') }}" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="jumlah_max">Jumlah Max</label>
                                    <input type="number" class="form-control form-control-sm" id="jumlah_max" name="jumlah_max"
                                           value="{{ request('jumlah_max') }}" placeholder="999999999">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.isi-saldo.indeks') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="topupTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice</th>
                                <th>User</th>
                                <th>Jumlah</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Tanggal Request</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topups as $topup)
                            <tr>
                                <td>{{ $topup->id }}</td>
                                <td>
                                    <strong class="text-primary invoice-number">{{ $topup->invoice_number }}</strong>
                                    <br><small class="text-muted">{{ $topup->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $topup->user->name }}</strong><br>
                                    <small class="text-muted">{{ $topup->user->email }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($topup->payment_method) }}</span>
                                    @if($topup->payment_channel)
                                        <br><small class="text-muted">{{ $topup->payment_channel }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $topup->status_color }}">{{ $topup->status_text }}</span>
                                </td>
                                <td>
                                    {{ $topup->created_at->format('d/m/Y H:i') }}
                                    @if($topup->confirmed_at)
                                        <br><small class="text-success">Dikonfirmasi: {{ $topup->confirmed_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.isi-saldo.tampil', $topup->id) }}"
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($topup->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm"
                                                    onclick="approveTopup({{ $topup->id }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="rejectTopup({{ $topup->id }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        <form action="{{ route('admin.isi-saldo.hapus', $topup->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    title="Hapus" onclick="return confirm('Hapus data topup ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tidak ada data topup</h5>
                                        @if(request()->hasAny(['status', 'invoice', 'user', 'tanggal_dari', 'tanggal_sampai', 'jumlah_min', 'jumlah_max']))
                                            <p class="text-muted">Coba ubah filter pencarian Anda</p>
                                            <a href="{{ route('admin.isi-saldo.indeks') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-times"></i> Reset Filter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($topups->hasPages())
            <div class="card-footer">
                {{ $topups->links() }}
            </div>
            @endif
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
    // Initialize DataTable
    $('#topupTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": false, // Disable default search since we have custom filters
        "ordering": true,
        "info": true,
        "paging": false,
        "order": [[ 0, "desc" ]], // Sort by ID descending
        "columnDefs": [
            { "orderable": false, "targets": -1 } // Disable sorting on action column
        ],
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)"
        }
    });

    // Auto-submit form on filter change (with debounce)
    let filterTimeout;
    $('#filterForm input, #filterForm select').on('input change', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500); // 500ms delay
    });

    // Quick filter buttons
    $('.quick-filter').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        $('#status').val(status);
        $('#filterForm').submit();
    });

    // Clear individual filter
    $('.clear-filter').on('click', function() {
        const target = $(this).data('target');
        $(target).val('');
        $('#filterForm').submit();
    });
});

function approveTopup(topupId) {
    $('#approveForm').attr('action', '/admin/isi-saldo/' + topupId + '/setujui');
    $('#approveModal').modal('show');
}

function rejectTopup(topupId) {
    $('#rejectForm').attr('action', '/admin/isi-saldo/' + topupId + '/tolak');
    $('#rejectModal').modal('show');
}

// Add some quick filter buttons
function addQuickFilters() {
    const quickFiltersHtml = `
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
    `;

    $('#filterCollapse .card-body').prepend(quickFiltersHtml);
}

// Initialize quick filters after DOM is ready
$(document).ready(function() {
    addQuickFilters();
});
</script>
@endpush
