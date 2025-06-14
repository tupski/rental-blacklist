@extends('layouts.admin')

@section('title', 'Manajemen Topup')
@section('page-title', 'Manajemen Topup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Topup</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Permintaan Topup</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('admin.isi-saldo.indeks') }}">Semua</a>
                            <a class="dropdown-item" href="{{ route('admin.isi-saldo.indeks', ['status' => 'pending']) }}">Pending</a>
                            <a class="dropdown-item" href="{{ route('admin.isi-saldo.indeks', ['status' => 'confirmed']) }}">Confirmed</a>
                            <a class="dropdown-item" href="{{ route('admin.isi-saldo.indeks', ['status' => 'rejected']) }}">Rejected</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="topupTable">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                    <strong>{{ $topup->user->name }}</strong><br>
                                    <small class="text-muted">{{ $topup->user->email }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>{{ $topup->payment_method ?? 'Transfer Bank' }}</td>
                                <td>
                                    <span class="badge badge-{{ $topup->status_color }}">{{ $topup->status_text }}</span>
                                </td>
                                <td>{{ $topup->created_at->format('d/m/Y H:i') }}</td>
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
                                <td colspan="7" class="text-center">Tidak ada data topup</td>
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

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $topups->where('status', 'pending')->count() }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $topups->where('status', 'confirmed')->count() }}</h3>
                <p>Confirmed</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $topups->where('status', 'rejected')->count() }}</h3>
                <p>Rejected</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp {{ number_format($topups->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}</h3>
                <p>Total Confirmed</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill"></i>
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
                        <label for="rejection_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
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
    $('#topupTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "paging": false,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)"
        }
    });
});

function approveTopup(topupId) {
    $('#approveForm').attr('action', '/admin/topup/' + topupId + '/approve');
    $('#approveModal').modal('show');
}

function rejectTopup(topupId) {
    $('#rejectForm').attr('action', '/admin/topup/' + topupId + '/reject');
    $('#rejectModal').modal('show');
}
</script>
@endpush
