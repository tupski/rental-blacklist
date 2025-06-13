@extends('layouts.admin')

@section('title', 'Detail Blacklist')
@section('page-title', 'Detail Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.blacklist.index') }}">Daftar Blacklist</a></li>
    <li class="breadcrumb-item active">Detail Blacklist</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Data Blacklist</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.blacklist.edit', $blacklist->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.blacklist.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $blacklist->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap:</strong></td>
                                <td>{{ $blacklist->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK:</strong></td>
                                <td>{{ $blacklist->nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $blacklist->no_hp }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Rental:</strong></td>
                                <td><span class="badge badge-info">{{ $blacklist->jenis_rental }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($blacklist->status_validitas === 'Valid')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($blacklist->status_validitas === 'Invalid')
                                        <span class="badge badge-danger">Invalid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Pelapor:</strong></td>
                                <td>{{ $blacklist->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td>{{ $blacklist->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $blacklist->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Alamat:</h5>
                        <p>{{ $blacklist->alamat ?: 'Tidak ada alamat' }}</p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Deskripsi Masalah:</h5>
                        <p>{{ $blacklist->deskripsi_masalah }}</p>
                    </div>
                </div>

                @if($blacklist->catatan_admin)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Catatan Admin:</h5>
                        <div class="alert alert-info">
                            {{ $blacklist->catatan_admin }}
                        </div>
                    </div>
                </div>
                @endif

                @if($blacklist->bukti && count($blacklist->bukti) > 0)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Bukti:</h5>
                        <div class="row">
                            @foreach($blacklist->bukti as $bukti)
                            <div class="col-md-3 mb-3">
                                @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/bukti/' . $bukti) }}"
                                         class="img-fluid img-thumbnail"
                                         style="max-height: 200px; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/bukti/' . $bukti) }}')">
                                @else
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file fa-3x text-muted"></i>
                                            <p class="mt-2">{{ $bukti }}</p>
                                            <a href="{{ asset('storage/bukti/' . $bukti) }}"
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Reports Section -->
        @if($relatedReports->count() > 0 || $guestReports->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Terkait NIK: {{ $blacklist->nik }}</h3>
            </div>
            <div class="card-body">
                @if($relatedReports->count() > 0)
                    <h5 class="text-primary">Laporan dari User Terdaftar ({{ $relatedReports->count() }})</h5>
                    <div class="timeline">
                        @foreach($relatedReports as $report)
                        <div class="time-label">
                            <span class="bg-primary">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-flag bg-{{ $report->status_validitas === 'Valid' ? 'success' : ($report->status_validitas === 'Invalid' ? 'danger' : 'warning') }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $report->created_at->format('H:i') }}
                                </span>
                                <h3 class="timeline-header">
                                    <span class="badge badge-info">{{ $report->jenis_rental }}</span>
                                    @if($report->status_validitas === 'Valid')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($report->status_validitas === 'Invalid')
                                        <span class="badge badge-danger">Invalid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </h3>
                                <div class="timeline-body">
                                    <strong>Pelapor:</strong> {{ $report->user->name ?? 'N/A' }}<br>
                                    <strong>Masalah:</strong> {{ Str::limit($report->deskripsi_masalah, 150) }}
                                    @if($report->alamat)
                                        <br><strong>Alamat:</strong> {{ $report->alamat }}
                                    @endif
                                </div>
                                <div class="timeline-footer">
                                    <a href="{{ route('admin.blacklist.show', $report->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if($guestReports->count() > 0)
                    <h5 class="text-success mt-4">Laporan dari Guest ({{ $guestReports->count() }})</h5>
                    <div class="timeline">
                        @foreach($guestReports as $guestReport)
                        <div class="time-label">
                            <span class="bg-success">{{ $guestReport->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-user bg-{{ $guestReport->status === 'approved' ? 'success' : ($guestReport->status === 'rejected' ? 'danger' : 'warning') }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $guestReport->created_at->format('H:i') }}
                                </span>
                                <h3 class="timeline-header">
                                    <span class="badge badge-info">{{ $guestReport->rental_type }}</span>
                                    @if($guestReport->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($guestReport->status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </h3>
                                <div class="timeline-body">
                                    <strong>Pelapor:</strong> {{ $guestReport->reporter_name }} ({{ $guestReport->reporter_email }})<br>
                                    <strong>Masalah:</strong> {{ Str::limit($guestReport->description, 150) }}
                                    @if($guestReport->rental_name)
                                        <br><strong>Nama Rental:</strong> {{ $guestReport->rental_name }}
                                    @endif
                                </div>
                                <div class="timeline-footer">
                                    <a href="{{ route('admin.guest-reports.show', $guestReport->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                @if($blacklist->status_validitas === 'Pending')
                <form action="{{ route('admin.blacklist.validate', $blacklist->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('Validasi data blacklist ini?')">
                        <i class="fas fa-check"></i> Validasi
                    </button>
                </form>
                <form action="{{ route('admin.blacklist.invalidate', $blacklist->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Tolak data blacklist ini?')">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.blacklist.edit', $blacklist->id) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit Data
                </a>

                <form action="{{ route('admin.blacklist.destroy', $blacklist->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Hapus data blacklist ini? Tindakan ini tidak dapat dibatalkan!')">
                        <i class="fas fa-trash"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik</h3>
            </div>
            <div class="card-body">
                <p><strong>Total Laporan NIK ini:</strong> {{ \App\Models\RentalBlacklist::where('nik', $blacklist->nik)->count() }}</p>
                <p><strong>Laporan dari User Berbeda:</strong> {{ \App\Models\RentalBlacklist::where('nik', $blacklist->nik)->distinct('user_id')->count('user_id') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti</h5>
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
function showImageModal(imageSrc) {
    $('#modalImage').attr('src', imageSrc);
    $('#imageModal').modal('show');
}
</script>
@endpush
