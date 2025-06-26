@extends('layouts.main')

@section('title', 'Detail Laporan Blacklist')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="display-6 fw-bold text-dark mb-1">
                        <i class="fas fa-info-circle text-primary me-3"></i>
                        Detail Laporan Blacklist
                    </h1>
                    <p class="text-muted mb-0">Informasi lengkap laporan blacklist</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    {{ $blacklist->nama_lengkap }}
                </h5>
                <span class="badge
                    @if($blacklist->status_validitas === 'Valid') bg-success
                    @elseif($blacklist->status_validitas === 'Pending') bg-warning
                    @else bg-danger @endif fs-6">
                    {{ $blacklist->status_validitas }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- 1. Informasi Pelapor -->
                @if($blacklist->tipe_pelapor === 'guest')
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-building text-primary me-2"></i>
                        Informasi Pelapor (Rental)
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nama Perusahaan</label>
                            <p class="mb-0 fw-medium">{{ $blacklist->nama_perusahaan_rental ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Penanggung Jawab</label>
                            <p class="mb-0">{{ $blacklist->nama_penanggung_jawab ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">No. WhatsApp</label>
                            <p class="mb-0">{{ $blacklist->no_wa_pelapor ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Email</label>
                            <p class="mb-0">{{ $blacklist->email_pelapor ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Alamat Usaha</label>
                            <p class="mb-0">{{ $blacklist->alamat_usaha ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Website/Instagram</label>
                            <p class="mb-0">
                                @if($blacklist->website_usaha)
                                    <a href="{{ $blacklist->website_usaha }}" target="_blank" class="text-decoration-none">
                                        {{ $blacklist->website_usaha }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-building text-primary me-2"></i>
                        Informasi Pelapor
                    </h6>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Pelapor:</strong> {{ $blacklist->user->name ?? 'N/A' }} ({{ $blacklist->user->email ?? 'N/A' }})
                        <br>
                        <small class="text-muted">Data pelapor dari akun yang terdaftar</small>
                    </div>
                </div>
                @endif

                <!-- 2. Data Penyewa -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-user text-success me-2"></i>
                        Data Penyewa
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                            <p class="mb-0 fw-medium">{{ $blacklist->nama_lengkap }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Jenis Kelamin</label>
                            <p class="mb-0">{{ $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">NIK</label>
                            <p class="mb-0 fw-medium">{{ $blacklist->nik ?: 'Tidak ada' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">No HP</label>
                            <p class="mb-0">{{ $blacklist->no_hp }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">Alamat</label>
                            <p class="mb-0">{{ $blacklist->alamat ?: 'Tidak ada alamat' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Foto Penyewa -->
                @if($blacklist->foto_penyewa && count($blacklist->foto_penyewa) > 0)
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-camera text-info me-2"></i>
                        Foto Penyewa
                    </h6>
                    <div class="row g-3">
                        @foreach($blacklist->foto_penyewa as $foto)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                                <div class="card-body p-2">
                                    <small class="text-muted">{{ basename($foto) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Foto KTP/SIM -->
                @if($blacklist->foto_ktp_sim && count($blacklist->foto_ktp_sim) > 0)
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-id-card text-warning me-2"></i>
                        Foto KTP/SIM
                    </h6>
                    <div class="row g-3">
                        @foreach($blacklist->foto_ktp_sim as $foto)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                                <div class="card-body p-2">
                                    <small class="text-muted">{{ basename($foto) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- 3. Detail Masalah -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-car text-primary me-2"></i>
                        Detail Masalah
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Kategori Rental</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $blacklist->jenis_rental }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Sewa</label>
                            <p class="mb-0">{{ $blacklist->tanggal_sewa ? \App\Helpers\DateHelper::formatIndonesian($blacklist->tanggal_sewa) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Kejadian</label>
                            <p class="mb-0">{{ $blacklist->tanggal_kejadian ? \App\Helpers\DateHelper::formatIndonesian($blacklist->tanggal_kejadian) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Jenis Kendaraan/Barang</label>
                            <p class="mb-0">{{ $blacklist->jenis_kendaraan ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nomor Polisi</label>
                            <p class="mb-0 font-monospace">{{ \App\Helpers\LicensePlateHelper::display($blacklist->nomor_polisi) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nilai Kerugian</label>
                            <p class="mb-0">
                                @if($blacklist->nilai_kerugian)
                                    <span class="text-danger fw-bold">{{ \App\Helpers\CurrencyHelper::format($blacklist->nilai_kerugian) }}</span>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jenis Masalah -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Jenis Masalah
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if($blacklist->jenis_laporan && is_array($blacklist->jenis_laporan))
                            @foreach($blacklist->jenis_laporan as $jenis)
                                <span class="badge bg-warning text-dark">{{ $jenis }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada data</span>
                        @endif
                    </div>
                </div>

                <!-- Status Penanganan -->
                @if($blacklist->status_penanganan && is_array($blacklist->status_penanganan))
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-tasks text-info me-2"></i>
                        Status Penanganan
                    </h6>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($blacklist->status_penanganan as $status)
                            @if($status === 'dilaporkan_polisi')
                                <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i> Sudah dilaporkan ke polisi</span>
                            @elseif($status === 'tidak_ada_respon')
                                <span class="badge bg-warning"><i class="fas fa-phone-slash me-1"></i> Tidak ada respon</span>
                            @elseif($status === 'proses_penyelesaian')
                                <span class="badge bg-info"><i class="fas fa-hourglass-half me-1"></i> Proses penyelesaian</span>
                            @elseif($status === 'lainnya')
                                <span class="badge bg-secondary"><i class="fas fa-ellipsis-h me-1"></i> Lainnya</span>
                            @endif
                        @endforeach
                    </div>

                    @if($blacklist->status_lainnya)
                        <div class="alert alert-light">
                            <strong>Keterangan Lainnya:</strong> {{ $blacklist->status_lainnya }}
                        </div>
                    @endif
                </div>
                @endif

                <!-- Kronologi -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-file-alt text-info me-2"></i>
                        Kronologi Kejadian
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $blacklist->kronologi }}</p>
                    </div>
                </div>

                <!-- Bukti -->
                @if($blacklist->bukti && count($blacklist->bukti) > 0)
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-paperclip text-secondary me-2"></i>
                        Bukti Pendukung
                    </h6>
                    <div class="row g-3">
                        @foreach($blacklist->bukti as $file)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center py-3">
                                    @php
                                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                        $isVideo = in_array(strtolower($extension), ['mp4', 'avi', 'mov']);
                                        $isPdf = strtolower($extension) === 'pdf';
                                    @endphp

                                    @if($isImage)
                                        <i class="fas fa-image text-success fs-2 mb-2"></i>
                                    @elseif($isVideo)
                                        <i class="fas fa-video text-danger fs-2 mb-2"></i>
                                    @elseif($isPdf)
                                        <i class="fas fa-file-pdf text-danger fs-2 mb-2"></i>
                                    @else
                                        <i class="fas fa-file text-muted fs-2 mb-2"></i>
                                    @endif

                                    <p class="small mb-2">{{ basename($file) }}</p>
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Persetujuan dan Pernyataan -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-file-signature text-success me-2"></i>
                        Persetujuan dan Pernyataan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Persetujuan</label>
                            <p class="mb-0">
                                @if($blacklist->persetujuan)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak disetujui</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nama Pelapor (TTD)</label>
                            <p class="mb-0">{{ $blacklist->nama_pelapor_ttd ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Pelapor -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-user-shield text-dark me-2"></i>
                        Informasi Sistem
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Laporan</label>
                            <p class="mb-0">{{ \App\Helpers\DateHelper::formatDenganWaktu($blacklist->created_at) }}</p>
                        </div>
                        @if($blacklist->updated_at != $blacklist->created_at)
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Terakhir Diupdate</label>
                            <p class="mb-0">{{ \App\Helpers\DateHelper::formatDenganWaktu($blacklist->updated_at) }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Status Validitas</label>
                            <p class="mb-0">
                                @if($blacklist->status_validitas === 'Valid')
                                    <span class="badge bg-success">Valid</span>
                                @elseif($blacklist->status_validitas === 'Invalid')
                                    <span class="badge bg-danger">Invalid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between">
                <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar
                </a>

                @if($blacklist->user_id === Auth::id())
                <div class="d-flex gap-2">
                    <a href="{{ route('dasbor.daftar-hitam.edit', $blacklist->id) }}" class="btn btn-success">
                        <i class="fas fa-edit me-2"></i>
                        Edit Laporan
                    </a>
                    <button onclick="deleteBlacklist({{ $blacklist->id }})" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Hapus Laporan
                    </button>
                </div>
                @endif
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
@endsection

@push('scripts')
<script>
function deleteBlacklist(id) {
    if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
        $.ajax({
            url: `/dasbor/daftar-hitam/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route('dasbor.daftar-hitam.indeks') }}';
                }
            },
            error: function(xhr) {
                console.error('Delete error:', xhr);
                alert('Terjadi kesalahan saat menghapus data');
            }
        });
    }
}

function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('downloadLink').href = imageSrc;

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endpush
