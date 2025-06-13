@extends('layouts.main')

@section('title', 'Detail Sponsor')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-6 fw-bold text-dark mb-2">
                                    <i class="fas fa-eye text-info me-3"></i>
                                    Detail Sponsor
                                </h1>
                                <p class="text-muted mb-1">
                                    Informasi lengkap sponsor: {{ $sponsor->name }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="d-flex gap-2 justify-content-lg-end">
                                    <a href="{{ route('admin.sponsors.edit', $sponsor) }}" class="btn btn-success">
                                        <i class="fas fa-edit me-2"></i>
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.sponsors.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sponsor Info -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informasi Sponsor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Nama Sponsor</label>
                                    <p class="fw-bold">{{ $sponsor->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Website</label>
                                    <p>
                                        <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ $sponsor->website_url }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($sponsor->description)
                        <div class="mb-3">
                            <label class="form-label text-muted">Deskripsi</label>
                            <p>{{ $sponsor->description }}</p>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Posisi Tampil</label>
                                    <p>
                                        <span class="badge 
                                            @if($sponsor->position === 'home_top') bg-primary
                                            @elseif($sponsor->position === 'home_bottom') bg-success
                                            @else bg-secondary @endif">
                                            @if($sponsor->position === 'home_top') Home - Atas Form Pencarian
                                            @elseif($sponsor->position === 'home_bottom') Home - Bawah Form Pencarian
                                            @else Footer - Semua Halaman @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Urutan Tampil</label>
                                    <p class="fw-bold">{{ $sponsor->sort_order }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Tanggal Mulai</label>
                                    <p>{{ $sponsor->start_date ? $sponsor->start_date->format('d F Y') : 'Tidak ditentukan' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Tanggal Berakhir</label>
                                    <p>{{ $sponsor->end_date ? $sponsor->end_date->format('d F Y') : 'Tidak ditentukan' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($sponsor->isCurrentlyActive())
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Dibuat</label>
                                    <p>{{ $sponsor->created_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Positions -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-desktop text-success me-2"></i>
                            Preview Tampilan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($sponsor->position === 'home_top')
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Posisi: Home - Atas Form Pencarian</h6>
                                <p class="mb-0">Logo akan tampil di halaman utama, di atas form pencarian dengan teks "Didukung oleh:"</p>
                            </div>
                        @elseif($sponsor->position === 'home_bottom')
                            <div class="alert alert-success">
                                <h6><i class="fas fa-info-circle me-2"></i>Posisi: Home - Bawah Form Pencarian</h6>
                                <p class="mb-0">Logo akan tampil di halaman utama, di bawah form pencarian tanpa teks tambahan</p>
                            </div>
                        @else
                            <div class="alert alert-secondary">
                                <h6><i class="fas fa-info-circle me-2"></i>Posisi: Footer - Semua Halaman</h6>
                                <p class="mb-0">Logo akan tampil di footer semua halaman dengan teks "Didukung oleh:"</p>
                            </div>
                        @endif

                        <div class="border rounded p-3 bg-light">
                            <div class="text-center">
                                @if($sponsor->position !== 'home_bottom')
                                    <small class="text-muted d-block mb-2">Didukung oleh:</small>
                                @endif
                                <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" 
                                     class="img-fluid" 
                                     style="max-height: {{ $sponsor->position === 'footer' ? '40px' : '50px' }}; max-width: {{ $sponsor->position === 'footer' ? '120px' : '150px' }};">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo & Actions -->
            <div class="col-lg-4">
                <!-- Logo -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-image text-primary me-2"></i>
                            Logo Sponsor
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" 
                             class="img-fluid mb-3" style="max-height: 150px; max-width: 250px;">
                        <p class="text-muted small mb-0">{{ $sponsor->name }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ $sponsor->website_url }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Kunjungi Website
                            </a>
                            <a href="{{ route('admin.sponsors.edit', $sponsor) }}" class="btn btn-success">
                                <i class="fas fa-edit me-2"></i>
                                Edit Sponsor
                            </a>
                            <button onclick="deleteSponsor({{ $sponsor->id }})" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>
                                Hapus Sponsor
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-info me-2"></i>
                            Informasi Tambahan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">ID Sponsor:</span>
                            <span class="small fw-bold">#{{ $sponsor->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Terakhir Update:</span>
                            <span class="small">{{ $sponsor->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Status Database:</span>
                            <span class="badge {{ $sponsor->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $sponsor->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus sponsor <strong>{{ $sponsor->name }}</strong>?</p>
                <p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSponsor(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
