@extends('layouts.admin')

@section('title', 'Detail Topup')
@section('page-title', 'Detail Topup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.isi-saldo.indeks') }}">Manajemen Topup</a></li>
    <li class="breadcrumb-item active">Detail Topup</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Permintaan Topup</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.isi-saldo.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID Topup:</strong></td>
                                <td>{{ $topup->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>User:</strong></td>
                                <td>
                                    <strong>{{ $topup->user->name }}</strong><br>
                                    <small class="text-muted">{{ $topup->user->email }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah:</strong></td>
                                <td><strong class="text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Metode Pembayaran:</strong></td>
                                <td>{{ $topup->payment_method ?? 'Transfer Bank' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $topup->status_color }}">{{ $topup->status_text }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tanggal Request:</strong></td>
                                <td>{{ $topup->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @if($topup->confirmed_at)
                            <tr>
                                <td><strong>Tanggal Konfirmasi:</strong></td>
                                <td>{{ $topup->confirmed_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($topup->paid_at)
                            <tr>
                                <td><strong>Tanggal Pembayaran:</strong></td>
                                <td>{{ $topup->paid_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($topup->expires_at)
                            <tr>
                                <td><strong>Kadaluarsa:</strong></td>
                                <td>{{ $topup->expires_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($topup->notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Catatan User:</h5>
                        <div class="alert alert-info">
                            {{ $topup->notes }}
                        </div>
                    </div>
                </div>
                @endif

                @if($topup->admin_notes && $topup->status === 'rejected')
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Alasan Penolakan:</h5>
                        <div class="alert alert-danger">
                            {{ $topup->admin_notes }}
                        </div>
                    </div>
                </div>
                @endif

                @if($topup->admin_notes && $topup->status === 'confirmed')
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Catatan Admin:</h5>
                        <div class="alert alert-success">
                            {{ $topup->admin_notes }}
                        </div>
                    </div>
                </div>
                @endif

                @if($topup->proof_of_payment)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Bukti Pembayaran:</h5>
                        <div class="row">
                            @if(is_array($topup->proof_of_payment))
                                @foreach($topup->proof_of_payment as $proof)
                                <div class="col-md-3 mb-3">
                                    @if(in_array(pathinfo($proof, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset('storage/' . $proof) }}"
                                             class="img-fluid img-thumbnail"
                                             style="max-height: 200px; cursor: pointer;"
                                             onclick="showImageModal('{{ asset('storage/' . $proof) }}')">
                                    @else
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                <p class="mt-2">{{ $proof }}</p>
                                                <a href="{{ asset('storage/' . $proof) }}"
                                                   class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="col-md-3 mb-3">
                                    <img src="{{ asset('storage/' . $topup->proof_of_payment) }}"
                                         class="img-fluid img-thumbnail"
                                         style="max-height: 200px; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/' . $topup->proof_of_payment) }}')">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                @if(in_array($topup->status, ['pending', 'pending_confirmation']))
                <form action="{{ route('admin.isi-saldo.setujui', $topup->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('Approve permintaan topup ini? Saldo akan ditambahkan ke akun user.')">
                        <i class="fas fa-check"></i> Approve Topup
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-block"
                        onclick="showRejectModal()">
                    <i class="fas fa-times"></i> Reject Topup
                </button>
                @endif

                <form action="{{ route('admin.isi-saldo.hapus', $topup->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Hapus data topup ini? Tindakan ini tidak dapat dibatalkan!')">
                        <i class="fas fa-trash"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi User</h3>
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $topup->user->name }}</p>
                <p><strong>Email:</strong> {{ $topup->user->email }}</p>
                <p><strong>Role:</strong>
                    <span class="badge badge-info">{{ ucfirst($topup->user->role) }}</span>
                </p>
                <p><strong>Saldo Saat Ini:</strong>
                    <strong class="text-success">Rp {{ number_format($topup->user->getCurrentBalance(), 0, ',', '.') }}</strong>
                </p>
                <p><strong>Total Topup:</strong> {{ \App\Models\TopupRequest::where('user_id', $topup->user_id)->where('status', 'confirmed')->count() }}</p>
            </div>
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
            <form action="{{ route('admin.isi-saldo.tolak', $topup->id) }}" method="POST">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal() {
    $('#rejectModal').modal('show');
}

function showImageModal(imageSrc) {
    $('#modalImage').attr('src', imageSrc);
    $('#imageModal').modal('show');
}
</script>
@endpush
