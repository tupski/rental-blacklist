@extends('layouts.admin')

@section('title', 'Detail Laporan Guest')
@section('page-title', 'Detail Laporan Guest')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.guest-reports.index') }}">Laporan Guest</a></li>
    <li class="breadcrumb-item active">Detail Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Laporan Guest</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.guest-reports.index') }}" class="btn btn-secondary btn-sm">
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
                                <td>{{ $report->reporter_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $report->reporter_email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $report->reporter_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Laporan:</strong></td>
                                <td>{{ $report->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Terlapor</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $report->reported_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK:</strong></td>
                                <td>{{ $report->reported_nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $report->reported_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $report->reported_address ?? '-' }}</td>
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
                                <td><span class="badge badge-info">{{ $report->rental_type }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Rental:</strong></td>
                                <td>{{ $report->rental_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Rental:</strong></td>
                                <td>
                                    @if($report->rental_date)
                                        {{ \Carbon\Carbon::parse($report->rental_date)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Durasi:</strong></td>
                                <td>{{ $report->rental_duration ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Deskripsi Masalah</h5>
                        <div class="alert alert-warning">
                            {{ $report->description }}
                        </div>
                    </div>
                </div>

                @if($report->evidence_files)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Bukti Pendukung</h5>
                        <div class="row">
                            @if(is_array($report->evidence_files))
                                @foreach($report->evidence_files as $file)
                                <div class="col-md-3 mb-3">
                                    @if(in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset('storage/guest-reports/' . $file) }}" 
                                             class="img-fluid img-thumbnail" 
                                             style="max-height: 200px; cursor: pointer;"
                                             onclick="showImageModal('{{ asset('storage/guest-reports/' . $file) }}')">
                                    @else
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                <p class="mt-2">{{ $file }}</p>
                                                <a href="{{ asset('storage/guest-reports/' . $file) }}" 
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
                                    <img src="{{ asset('storage/guest-reports/' . $report->evidence_files) }}" 
                                         class="img-fluid img-thumbnail" 
                                         style="max-height: 200px; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/guest-reports/' . $report->evidence_files) }}')">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($report->admin_notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Catatan Admin</h5>
                        <div class="alert alert-info">
                            {{ $report->admin_notes }}
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
                        @if($report->status === 'pending')
                            <span class="badge badge-warning badge-lg">Pending Review</span>
                        @elseif($report->status === 'approved')
                            <span class="badge badge-success badge-lg">Approved</span>
                        @elseif($report->status === 'rejected')
                            <span class="badge badge-danger badge-lg">Rejected</span>
                        @else
                            <span class="badge badge-secondary badge-lg">{{ ucfirst($report->status) }}</span>
                        @endif
                    </div>
                </div>

                @if($report->status === 'pending')
                <form action="{{ route('admin.guest-reports.approve', $report->id) }}" method="POST" class="mb-2">
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
                
                <a href="{{ route('admin.guest-reports.edit', $report->id) }}" 
                   class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit Laporan
                </a>
                
                <form action="{{ route('admin.guest-reports.destroy', $report->id) }}" method="POST" class="mt-2">
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
                    <div class="time-label">
                        <span class="bg-primary">{{ $report->created_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-flag bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $report->created_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Dibuat</h3>
                            <div class="timeline-body">
                                Laporan dibuat oleh {{ $report->reporter_name }}
                            </div>
                        </div>
                    </div>
                    
                    @if($report->approved_at)
                    <div class="time-label">
                        <span class="bg-success">{{ $report->approved_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-check bg-green"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $report->approved_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Disetujui</h3>
                            <div class="timeline-body">
                                Laporan disetujui dan ditambahkan ke blacklist
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($report->rejected_at)
                    <div class="time-label">
                        <span class="bg-danger">{{ $report->rejected_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-times bg-red"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $report->rejected_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Laporan Ditolak</h3>
                            <div class="timeline-body">
                                {{ $report->admin_notes ?? 'Laporan ditolak oleh admin' }}
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
            <form action="{{ route('admin.guest-reports.reject', $report->id) }}" method="POST">
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
