@extends('layouts.admin')

@section('title', 'Kelola Akun Rental')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kelola Akun Rental</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Akun Rental</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Status Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $statusCounts['all'] }}</h3>
                            <p>Total Akun</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.rental-accounts.index', ['status' => 'all']) }}" class="small-box-footer">
                            Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $statusCounts['active'] }}</h3>
                            <p>Aktif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{ route('admin.rental-accounts.index', ['status' => 'active']) }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $statusCounts['pending'] }}</h3>
                            <p>Pending</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('admin.rental-accounts.index', ['status' => 'pending']) }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $statusCounts['needs_revision'] }}</h3>
                            <p>Butuh Revisi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <a href="{{ route('admin.rental-accounts.index', ['status' => 'needs_revision']) }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $statusCounts['suspended'] }}</h3>
                            <p>Dibekukan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ban"></i>
                        </div>
                        <a href="{{ route('admin.rental-accounts.index', ['status' => 'suspended']) }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter & Pencarian</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.rental-accounts.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="needs_revision" {{ $status === 'needs_revision' ? 'selected' : '' }}>Butuh Revisi</option>
                                        <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Dibekukan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">Pencarian</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                           value="{{ $search }}" placeholder="Nama, email, atau nama rental...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.rental-accounts.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Akun Rental</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nama Rental</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                            <tr>
                                <td>
                                    <strong>{{ $account->name }}</strong>
                                    @if($account->email_verified_at)
                                        <i class="fas fa-check-circle text-success ml-1" title="Email terverifikasi"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning ml-1" title="Email belum terverifikasi"></i>
                                    @endif
                                </td>
                                <td>{{ $account->email }}</td>
                                <td>
                                    @if($account->rentalRegistration)
                                        {{ $account->rentalRegistration->nama_rental }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->account_status === 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @elseif($account->account_status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($account->account_status === 'needs_revision')
                                        <span class="badge badge-secondary">Butuh Revisi</span>
                                    @elseif($account->account_status === 'suspended')
                                        <span class="badge badge-danger">Dibekukan</span>
                                    @endif
                                </td>
                                <td>{{ $account->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.rental-accounts.show', $account) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($account->account_status === 'pending')
                                            <button class="btn btn-sm btn-success"
                                                    onclick="approveAccount({{ $account->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        @if($account->account_status !== 'suspended')
                                            <button class="btn btn-sm btn-warning"
                                                    onclick="requestRevision({{ $account->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="suspendAccount({{ $account->id }})">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-success"
                                                    onclick="reactivateAccount({{ $account->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada akun rental ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($accounts->hasPages())
                <div class="card-footer">
                    {{ $accounts->links() }}
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Modals -->
<!-- Revision Request Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Minta Revisi Data</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="revisionForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="revision_notes">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="revision_notes" name="revision_notes" rows="4"
                                  placeholder="Jelaskan data apa yang perlu direvisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspension Modal -->
<div class="modal fade" id="suspensionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bekukan Akun</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="suspensionForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="suspension_reason">Alasan Pembekuan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="suspension_reason" name="suspension_reason" rows="3"
                                  placeholder="Jelaskan alasan pembekuan akun..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jenis Pembekuan <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="suspension_type"
                                       id="permanent" value="permanent" checked>
                                <label class="form-check-label" for="permanent">
                                    Permanen
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="suspension_type"
                                       id="temporary" value="temporary">
                                <label class="form-check-label" for="temporary">
                                    Sementara
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="suspension_days_group" style="display: none;">
                        <label for="suspension_days">Durasi (Hari) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="suspension_days" name="suspension_days"
                               min="1" max="365" placeholder="Masukkan jumlah hari...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Bekukan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentAccountId = null;

// Show/hide suspension days field based on type
$('input[name="suspension_type"]').change(function() {
    if ($(this).val() === 'temporary') {
        $('#suspension_days_group').show();
        $('#suspension_days').prop('required', true);
    } else {
        $('#suspension_days_group').hide();
        $('#suspension_days').prop('required', false);
    }
});

// Approve account
function approveAccount(accountId) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan akun ini?')) {
        $.post(`{{ route('admin.rental-accounts.approve', '') }}/${accountId}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            }
        })
        .fail(function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response.error || 'Terjadi kesalahan');
        });
    }
}

// Request revision
function requestRevision(accountId) {
    currentAccountId = accountId;
    $('#revisionModal').modal('show');
}

// Handle revision form submission
$('#revisionForm').submit(function(e) {
    e.preventDefault();

    const notes = $('#revision_notes').val().trim();
    if (!notes) {
        toastr.error('Catatan revisi harus diisi');
        return;
    }

    $.post(`{{ route('admin.rental-accounts.request-revision', '') }}/${currentAccountId}`, {
        _token: '{{ csrf_token() }}',
        revision_notes: notes
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#revisionModal').modal('hide');
            location.reload();
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response.error || 'Terjadi kesalahan');
    });
});

// Suspend account
function suspendAccount(accountId) {
    currentAccountId = accountId;
    $('#suspensionModal').modal('show');
}

// Handle suspension form submission
$('#suspensionForm').submit(function(e) {
    e.preventDefault();

    const reason = $('#suspension_reason').val().trim();
    const type = $('input[name="suspension_type"]:checked').val();
    const days = $('#suspension_days').val();

    if (!reason) {
        toastr.error('Alasan pembekuan harus diisi');
        return;
    }

    if (type === 'temporary' && (!days || days < 1)) {
        toastr.error('Durasi pembekuan harus diisi untuk pembekuan sementara');
        return;
    }

    $.post(`{{ route('admin.rental-accounts.suspend', '') }}/${currentAccountId}`, {
        _token: '{{ csrf_token() }}',
        suspension_reason: reason,
        suspension_type: type,
        suspension_days: days
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#suspensionModal').modal('hide');
            location.reload();
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response.error || 'Terjadi kesalahan');
    });
});

// Reactivate account
function reactivateAccount(accountId) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan kembali akun ini?')) {
        $.post(`{{ route('admin.rental-accounts.reactivate', '') }}/${accountId}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            }
        })
        .fail(function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response.error || 'Terjadi kesalahan');
        });
    }
}

// Reset modals when hidden
$('#revisionModal').on('hidden.bs.modal', function() {
    $('#revisionForm')[0].reset();
    currentAccountId = null;
});

$('#suspensionModal').on('hidden.bs.modal', function() {
    $('#suspensionForm')[0].reset();
    $('#suspension_days_group').hide();
    $('#suspension_days').prop('required', false);
    currentAccountId = null;
});
</script>
@endpush
