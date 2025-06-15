@extends('layouts.admin')

@section('title', 'Detail Laporan Guest')
@section('page-title', 'Detail Laporan Guest')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.laporan-tamu.indeks') }}">Laporan Guest</a></li>
    <li class="breadcrumb-item active">Detail Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Laporan Guest</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.laporan-tamu.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Pelapor</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $guestReport->nama_pelapor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $guestReport->email_pelapor }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $guestReport->no_hp_pelapor ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Laporan:</strong></td>
                                <td>{{ $guestReport->created_at ? $guestReport->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Terlapor</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $guestReport->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK:</strong></td>
                                <td>{{ $guestReport->nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin:</strong></td>
                                <td>{{ $guestReport->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $guestReport->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $guestReport->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Detail Rental</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Jenis Rental:</strong></td>
                                <td><span class="badge badge-info">{{ $guestReport->jenis_rental }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Laporan:</strong></td>
                                <td>
                                    @if(is_array($guestReport->jenis_laporan))
                                        @foreach($guestReport->jenis_laporan as $type)
                                            <span class="badge badge-secondary mr-1">{{ $type }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">{{ $guestReport->jenis_laporan }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kejadian:</strong></td>
                                <td>
                                    @if($guestReport->tanggal_kejadian)
                                        {{ $guestReport->tanggal_kejadian->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($guestReport->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($guestReport->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Kronologi Kejadian</h5>
                        <div class="alert alert-warning">
                            {{ $guestReport->kronologi }}
                        </div>
                    </div>
                </div>

                @if($guestReport->bukti)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Bukti Pendukung</h5>
                        <div class="row">
                            @if(is_array($guestReport->bukti))
                                @foreach($guestReport->bukti as $file)
                                <div class="col-md-3 mb-3">
                                    @if(in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset('storage/' . $file) }}"
                                             class="img-fluid img-thumbnail"
                                             style="max-height: 200px; cursor: pointer;"
                                             onclick="showImageModal('{{ asset('storage/' . $file) }}')">
                                    @else
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                <p class="mt-2">{{ basename($file) }}</p>
                                                <a href="{{ asset('storage/' . $file) }}"
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
                                    <img src="{{ asset('storage/' . $guestReport->bukti) }}"
                                         class="img-fluid img-thumbnail"
                                         style="max-height: 200px; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/' . $guestReport->bukti) }}')">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($guestReport->catatan_admin)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Catatan Admin</h5>
                        <div class="alert alert-info">
                            {{ $guestReport->catatan_admin }}
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
                <h3 class="card-title">Status & Aksi</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Status Saat Ini:</label>
                    <div>
                        @if($guestReport->status === 'pending')
                            <span class="badge badge-warning badge-lg">Pending Review</span>
                        @elseif($guestReport->status === 'approved')
                            <span class="badge badge-success badge-lg">Approved</span>
                        @elseif($guestReport->status === 'rejected')
                            <span class="badge badge-danger badge-lg">Rejected</span>
                        @else
                            <span class="badge badge-secondary badge-lg">{{ ucfirst($guestReport->status) }}</span>
                        @endif
                    </div>
                </div>

                @if($guestReport->status === 'pending')
                <form action="{{ route('admin.laporan-tamu.setujui', $guestReport) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('Approve laporan ini? Data akan ditambahkan ke blacklist.')">
                        <i class="fas fa-check"></i> Approve Laporan
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-block"
                        onclick="showRejectModal()">
                    <i class="fas fa-times"></i> Reject Laporan
                </button>
                @endif

                <a href="{{ route('admin.laporan-tamu.edit', $guestReport) }}"
                   class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit Laporan
                </a>

                <form action="{{ route('admin.laporan-tamu.hapus', $guestReport) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Hapus laporan ini? Tindakan ini tidak dapat dibatalkan!')">
                        <i class="fas fa-trash"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($guestReport->created_at)
                    <div class="time-label">
                        <span class="bg-primary">{{ $guestReport->created_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-flag bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $guestReport->created_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Dibuat</h3>
                            <div class="timeline-body">
                                Laporan dibuat oleh {{ $guestReport->nama_pelapor }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($guestReport->status === 'approved' && $guestReport->updated_at)
                    <div class="time-label">
                        <span class="bg-success">{{ $guestReport->updated_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-check bg-green"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $guestReport->updated_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Disetujui</h3>
                            <div class="timeline-body">
                                Laporan disetujui dan ditambahkan ke blacklist
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($guestReport->status === 'rejected' && $guestReport->updated_at)
                    <div class="time-label">
                        <span class="bg-danger">{{ $guestReport->updated_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-times bg-red"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $guestReport->updated_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Ditolak</h3>
                            <div class="timeline-body">
                                {{ $guestReport->catatan_admin ?? 'Laporan ditolak oleh admin' }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
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
            <form action="{{ route('admin.laporan-tamu.tolak', $guestReport) }}" method="POST">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pendukung</h5>
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
