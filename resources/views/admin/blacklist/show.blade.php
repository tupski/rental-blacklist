@extends('layouts.admin')

@section('title', 'Detail Blacklist')
@section('page-title', 'Detail Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.daftar-hitam.indeks') }}">Daftar Blacklist</a></li>
    <li class="breadcrumb-item active">Detail Blacklist</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Data Blacklist</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.daftar-hitam.edit', $blacklist->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.daftar-hitam.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- 1. Informasi Pelapor -->
                @if($blacklist->tipe_pelapor === 'guest')
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-building me-2"></i>
                            Informasi Pelapor (Rental)
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama Perusahaan:</strong></td>
                                <td>{{ $blacklist->nama_perusahaan_rental ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Penanggung Jawab:</strong></td>
                                <td>{{ $blacklist->nama_penanggung_jawab ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. WhatsApp:</strong></td>
                                <td>{{ $blacklist->no_wa_pelapor ?: 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $blacklist->email_pelapor ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Usaha:</strong></td>
                                <td>{{ $blacklist->alamat_usaha ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Website/Instagram:</strong></td>
                                <td>
                                    @if($blacklist->website_usaha)
                                        <a href="{{ $blacklist->website_usaha }}" target="_blank">{{ $blacklist->website_usaha }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @else
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-building me-2"></i>
                            Informasi Pelapor (Rental)
                        </h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pelapor:</strong> {{ $blacklist->user->name ?? 'N/A' }} ({{ $blacklist->user->email ?? 'N/A' }})
                            <br>
                            <small class="text-muted">Data pelapor dari akun yang terdaftar</small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 2. Data Penyewa -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-user me-2"></i>
                            Data Penyewa
                        </h5>
                    </div>
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
                                <td><strong>Jenis Kelamin:</strong></td>
                                <td>{{ $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK:</strong></td>
                                <td>{{ $blacklist->nik ?: 'Tidak ada' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $blacklist->no_hp }}</td>
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
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td>{{ \App\Helpers\DateHelper::formatDenganWaktu($blacklist->created_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ \App\Helpers\DateHelper::formatDenganWaktu($blacklist->updated_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pelaporan:</strong></td>
                                <td>{{ $blacklist->tanggal_pelaporan ? \App\Helpers\DateHelper::formatDenganWaktu($blacklist->tanggal_pelaporan) : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary">Alamat Lengkap:</h5>
                        <p>{{ $blacklist->alamat ?: 'Tidak ada alamat' }}</p>
                    </div>
                </div>

                <!-- Foto Penyewa -->
                @if($blacklist->foto_penyewa && count($blacklist->foto_penyewa) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary">Foto Penyewa:</h5>
                        <div class="row">
                            @foreach($blacklist->foto_penyewa as $foto)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     class="img-fluid img-thumbnail"
                                     style="max-height: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Foto KTP/SIM -->
                @if($blacklist->foto_ktp_sim && count($blacklist->foto_ktp_sim) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary">Foto KTP/SIM:</h5>
                        <div class="row">
                            @foreach($blacklist->foto_ktp_sim as $foto)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     class="img-fluid img-thumbnail"
                                     style="max-height: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 3. Detail Masalah -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Detail Masalah
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kategori Rental:</strong></td>
                                <td><span class="badge badge-info">{{ $blacklist->jenis_rental }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Masalah:</strong></td>
                                <td>
                                    @if($blacklist->jenis_laporan && is_array($blacklist->jenis_laporan))
                                        @foreach($blacklist->jenis_laporan as $jenis)
                                            <span class="badge badge-warning mr-1">{{ $jenis }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Sewa:</strong></td>
                                <td>{{ $blacklist->tanggal_sewa ? \App\Helpers\DateHelper::formatIndonesian($blacklist->tanggal_sewa) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kejadian:</strong></td>
                                <td>{{ $blacklist->tanggal_kejadian ? \App\Helpers\DateHelper::formatIndonesian($blacklist->tanggal_kejadian) : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Jenis Kendaraan/Barang:</strong></td>
                                <td>{{ $blacklist->jenis_kendaraan ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Polisi:</strong></td>
                                <td class="font-monospace">{{ \App\Helpers\LicensePlateHelper::display($blacklist->nomor_polisi) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nilai Kerugian:</strong></td>
                                <td>
                                    @if($blacklist->nilai_kerugian)
                                        <span class="text-danger font-weight-bold">{{ \App\Helpers\CurrencyHelper::format($blacklist->nilai_kerugian) }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <h6 class="text-primary mt-3">Kronologi Kejadian:</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $blacklist->kronologi }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Status Penanganan -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-tasks me-2"></i>
                            Status Penanganan
                        </h5>
                        @if($blacklist->status_penanganan && is_array($blacklist->status_penanganan))
                            <div class="mb-3">
                                @foreach($blacklist->status_penanganan as $status)
                                    @if($status === 'dilaporkan_polisi')
                                        <span class="badge badge-danger mr-2"><i class="fas fa-shield-alt me-1"></i> Sudah dilaporkan ke polisi</span>
                                    @elseif($status === 'tidak_ada_respon')
                                        <span class="badge badge-warning mr-2"><i class="fas fa-phone-slash me-1"></i> Tidak ada respon</span>
                                    @elseif($status === 'proses_penyelesaian')
                                        <span class="badge badge-info mr-2"><i class="fas fa-hourglass-half me-1"></i> Proses penyelesaian</span>
                                    @elseif($status === 'lainnya')
                                        <span class="badge badge-secondary mr-2"><i class="fas fa-ellipsis-h me-1"></i> Lainnya</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if($blacklist->status_lainnya)
                            <div class="alert alert-light">
                                <strong>Keterangan Lainnya:</strong> {{ $blacklist->status_lainnya }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 5. Persetujuan dan Pernyataan -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-file-signature me-2"></i>
                            Persetujuan dan Pernyataan
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Persetujuan:</strong></td>
                                        <td>
                                            @if($blacklist->persetujuan)
                                                <span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-times"></i> Tidak disetujui</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Pelapor (TTD):</strong></td>
                                        <td>{{ $blacklist->nama_pelapor_ttd ?: 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($blacklist->catatan_admin)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary">Catatan Admin:</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-sticky-note me-2"></i>
                            {{ $blacklist->catatan_admin }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Bukti Pendukung -->
                @if($blacklist->bukti && count($blacklist->bukti) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-paperclip me-2"></i>
                            Bukti Pendukung
                        </h5>
                        <div class="row">
                            @foreach($blacklist->bukti as $bukti)
                            <div class="col-md-3 mb-3">
                                @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $bukti) }}"
                                             class="card-img-top"
                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="showImageModal('{{ asset('storage/' . $bukti) }}')">
                                        <div class="card-body p-2">
                                            <small class="text-muted">{{ basename($bukti) }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="card">
                                        <div class="card-body text-center">
                                            @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['pdf']))
                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                            @elseif(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                <i class="fas fa-file-word fa-3x text-primary"></i>
                                            @elseif(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov']))
                                                <i class="fas fa-file-video fa-3x text-info"></i>
                                            @else
                                                <i class="fas fa-file fa-3x text-muted"></i>
                                            @endif
                                            <p class="mt-2 small">{{ basename($bukti) }}</p>
                                            <a href="{{ asset('storage/' . $bukti) }}"
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
                            <span class="bg-primary">{{ \App\Helpers\DateHelper::formatSingkat($report->created_at) }}</span>
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
                                    <strong>Masalah:</strong> {{ Str::limit($report->kronologi, 150) }}
                                    @if($report->alamat)
                                        <br><strong>Alamat:</strong> {{ $report->alamat }}
                                    @endif
                                </div>
                                <div class="timeline-footer">
                                    <a href="{{ route('admin.daftar-hitam.tampil', $report->id) }}" class="btn btn-sm btn-primary">
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
                            <span class="bg-success">{{ \App\Helpers\DateHelper::formatSingkat($guestReport->created_at) }}</span>
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
                <form action="{{ route('admin.daftar-hitam.validasi', $blacklist->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('Validasi data blacklist ini?')">
                        <i class="fas fa-check"></i> Validasi
                    </button>
                </form>
                <form action="{{ route('admin.daftar-hitam.batalkan', $blacklist->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Tolak data blacklist ini?')">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.daftar-hitam.edit', $blacklist->id) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit Data
                </a>

                <form action="{{ route('admin.daftar-hitam.hapus', $blacklist->id) }}" method="POST" class="mt-2">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Lihat Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Gambar">
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('downloadLink').href = imageSrc;

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection

@push('scripts')
<script>
function showImageModal(imageSrc) {
    $('#modalImage').attr('src', imageSrc);
    $('#imageModal').modal('show');
}
</script>
@endpush
