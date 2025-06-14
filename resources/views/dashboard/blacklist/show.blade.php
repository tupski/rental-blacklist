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
                <!-- Data Pribadi -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-user text-success me-2"></i>
                        Data Pribadi
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                            <p class="mb-0 fw-medium">{{ $blacklist->nama_lengkap }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">NIK</label>
                            <p class="mb-0 fw-medium">{{ $blacklist->nik }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Jenis Kelamin</label>
                            <p class="mb-0">{{ $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">No HP</label>
                            <p class="mb-0">{{ $blacklist->no_hp }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">Alamat</label>
                            <p class="mb-0">{{ $blacklist->alamat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Rental -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-car text-primary me-2"></i>
                        Data Rental
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Jenis Rental</label>
                            <p class="mb-0">{{ $blacklist->jenis_rental }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Kejadian</label>
                            <p class="mb-0">{{ $blacklist->tanggal_kejadian->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jenis Laporan -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Jenis Laporan
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($blacklist->jenis_laporan as $jenis)
                            <span class="badge bg-warning text-dark">
                                @switch($jenis)
                                    @case('percobaan_penipuan')
                                        Percobaan Penipuan
                                        @break
                                    @case('penipuan')
                                        Penipuan
                                        @break
                                    @case('tidak_mengembalikan_barang')
                                        Tidak Mengembalikan Barang
                                        @break
                                    @case('identitas_palsu')
                                        Identitas Palsu
                                        @break
                                    @case('sindikat')
                                        Sindikat
                                        @break
                                    @case('merusak_barang')
                                        Merusak Barang
                                        @break
                                    @default
                                        {{ $jenis }}
                                @endswitch
                            </span>
                        @endforeach
                    </div>
                </div>

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

                <!-- Info Pelapor -->
                <div class="col-12">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-user-shield text-dark me-2"></i>
                        Informasi Pelapor
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Nama Pelapor</label>
                            <p class="mb-0">{{ $blacklist->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Laporan</label>
                            <p class="mb-0">{{ $blacklist->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        @if($blacklist->updated_at != $blacklist->created_at)
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Terakhir Diupdate</label>
                            <p class="mb-0">{{ $blacklist->updated_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        @endif
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
@endsection

@push('scripts')
<script>
function deleteBlacklist(id) {
    if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
        $.ajax({
            url: `/dashboard/blacklist/${id}`,
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
</script>
@endpush
