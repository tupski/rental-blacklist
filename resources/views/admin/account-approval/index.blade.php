@extends('layouts.admin')

@section('title', 'Persetujuan Akun')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Persetujuan Akun</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Persetujuan Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-clock mr-2"></i>
                        Akun Menunggu Persetujuan
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $pendingUsers->total() }} Pending</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($pendingUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingUsers as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->nik)
                                                    <br><small class="text-muted">NIK: {{ $user->nik }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->role === 'pengusaha_rental')
                                                    <span class="badge badge-info">Pemilik Rental</span>
                                                @else
                                                    <span class="badge badge-secondary">User Umum</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            onclick="approveUser({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="rejectUser({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $pendingUsers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-success mb-3">Tidak Ada Akun Pending</h4>
                            <p class="text-muted mb-4">Semua akun sudah disetujui atau ditolak.</p>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-info-circle text-info mr-2"></i>
                                                Informasi
                                            </h6>
                                            <p class="card-text small mb-0">
                                                Akun baru yang mendaftar akan muncul di sini untuk persetujuan admin.
                                                Anda akan menerima notifikasi ketika ada akun baru yang perlu disetujui.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Akun</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui akun <strong id="approveUserName"></strong>?</p>
                <p class="text-muted">Akun akan langsung aktif dan dapat menggunakan semua fitur.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Akun</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak akun <strong id="rejectUserName"></strong>?</p>
                    <div class="form-group">
                        <label for="reason">Alasan Penolakan:</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                                  placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Peringatan:</strong> Akun akan dihapus permanen setelah ditolak.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveUser(userId, userName) {
    $('#approveUserName').text(userName);
    $('#approveForm').attr('action', `/admin/account-approval/${userId}/approve`);
    $('#approveModal').modal('show');
}

function rejectUser(userId, userName) {
    $('#rejectUserName').text(userName);
    $('#rejectForm').attr('action', `/admin/account-approval/${userId}/reject`);
    $('#rejectModal').modal('show');
}
</script>
@endpush
