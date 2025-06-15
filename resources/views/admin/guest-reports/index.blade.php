@extends('layouts.admin')

@section('title', 'Laporan Guest')
@section('page-title', 'Laporan Guest')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Guest</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Laporan dari Guest</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('admin.laporan-tamu.indeks') }}">Semua</a>
                            <a class="dropdown-item" href="{{ route('admin.laporan-tamu.indeks', ['status' => 'pending']) }}">Pending</a>
                            <a class="dropdown-item" href="{{ route('admin.laporan-tamu.indeks', ['status' => 'approved']) }}">Approved</a>
                            <a class="dropdown-item" href="{{ route('admin.laporan-tamu.indeks', ['status' => 'rejected']) }}">Rejected</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="guestReportsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pelapor</th>
                                <th>Email</th>
                                <th>Nama Terlapor</th>
                                <th>NIK</th>
                                <th>Jenis Rental</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->nama_pelapor }}</td>
                                <td>{{ $report->email_pelapor }}</td>
                                <td>{{ $report->nama_lengkap }}</td>
                                <td>{{ $report->nik }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $report->jenis_rental }}</span>
                                </td>
                                <td>
                                    @if($report->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($report->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($report->status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($report->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.laporan-tamu.tampil', $report) }}"
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($report->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm"
                                                    onclick="approveReport({{ $report->id }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="rejectReport({{ $report->id }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('admin.laporan-tamu.edit', $report) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.laporan-tamu.hapus', $report) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    title="Hapus" onclick="return confirm('Hapus laporan ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada laporan guest</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($reports->hasPages())
            <div class="card-footer">
                {{ $reports->links() }}
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
                <h3>{{ $reports->where('status', 'pending')->count() }}</h3>
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
                <h3>{{ $reports->where('status', 'approved')->count() }}</h3>
                <p>Approved</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $reports->where('status', 'rejected')->count() }}</h3>
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
                <h3>{{ $reports->count() }}</h3>
                <p>Total Laporan</p>
            </div>
            <div class="icon">
                <i class="fas fa-flag"></i>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Laporan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui laporan ini?</p>
                    <p class="text-muted">Laporan yang disetujui akan ditambahkan ke database blacklist.</p>
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
                <h5 class="modal-title">Reject Laporan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_admin">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_admin" name="catatan_admin"
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
    $('#guestReportsTable').DataTable({
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

function approveReport(reportId) {
    $('#approveForm').attr('action', '/admin/laporan-tamu/' + reportId + '/setujui');
    $('#approveModal').modal('show');
}

function rejectReport(reportId) {
    $('#rejectForm').attr('action', '/admin/laporan-tamu/' + reportId + '/tolak');
    $('#rejectModal').modal('show');
}
</script>
@endpush
