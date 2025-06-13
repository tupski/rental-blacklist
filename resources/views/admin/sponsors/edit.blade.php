@extends('layouts.main')

@section('title', 'Edit Sponsor')

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
                                    <i class="fas fa-edit text-success me-3"></i>
                                    Edit Sponsor
                                </h1>
                                <p class="text-muted mb-1">
                                    Edit informasi sponsor: {{ $sponsor->name }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
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

        <!-- Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informasi Sponsor
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.sponsors.update', $sponsor) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Sponsor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $sponsor->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website_url" class="form-label">Website URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control @error('website_url') is-invalid @enderror" 
                                               id="website_url" name="website_url" value="{{ old('website_url', $sponsor->website_url) }}" 
                                               placeholder="https://example.com" required>
                                        @error('website_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah logo.</div>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Deskripsi singkat tentang sponsor">{{ old('description', $sponsor->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Posisi Tampil <span class="text-danger">*</span></label>
                                        <select class="form-select @error('position') is-invalid @enderror" 
                                                id="position" name="position" required>
                                            <option value="">Pilih Posisi</option>
                                            <option value="home_top" {{ old('position', $sponsor->position) == 'home_top' ? 'selected' : '' }}>
                                                Home - Atas Form Pencarian
                                            </option>
                                            <option value="home_bottom" {{ old('position', $sponsor->position) == 'home_bottom' ? 'selected' : '' }}>
                                                Home - Bawah Form Pencarian
                                            </option>
                                            <option value="footer" {{ old('position', $sponsor->position) == 'footer' ? 'selected' : '' }}>
                                                Footer - Semua Halaman
                                            </option>
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Urutan Tampil</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $sponsor->sort_order) }}" 
                                               min="0" placeholder="0">
                                        <div class="form-text">Semakin kecil angka, semakin awal tampil</div>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" name="start_date" 
                                               value="{{ old('start_date', $sponsor->start_date?->format('Y-m-d')) }}">
                                        <div class="form-text">Kosongkan jika mulai sekarang</div>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Tanggal Berakhir</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" name="end_date" 
                                               value="{{ old('end_date', $sponsor->end_date?->format('Y-m-d')) }}">
                                        <div class="form-text">Kosongkan jika tidak ada batas waktu</div>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', $sponsor->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>
                                    Update Sponsor
                                </button>
                                <a href="{{ route('admin.sponsors.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="col-lg-4">
                <!-- Current Logo -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-image text-info me-2"></i>
                            Logo Saat Ini
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" 
                             class="img-fluid mb-2" style="max-height: 100px; max-width: 200px;">
                        <p class="text-muted small mb-0">{{ $sponsor->name }}</p>
                    </div>
                </div>

                <!-- New Logo Preview -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-eye text-warning me-2"></i>
                            Preview Logo Baru
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div id="logoPreview" class="mb-3" style="min-height: 100px; display: flex; align-items: center; justify-content: center; border: 2px dashed #dee2e6; border-radius: 8px;">
                            <div class="text-muted">
                                <i class="fas fa-upload fs-1 mb-2"></i>
                                <p class="mb-0">Upload logo baru untuk preview</p>
                            </div>
                        </div>
                        <small class="text-muted">Logo baru akan tampil seperti ini</small>
                    </div>
                </div>

                <!-- Info -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-info bg-opacity-10 border-0">
                        <h6 class="card-title mb-0 text-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Status Sponsor
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Status:</span>
                            <span class="badge {{ $sponsor->isCurrentlyActive() ? 'bg-success' : 'bg-danger' }}">
                                {{ $sponsor->isCurrentlyActive() ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Posisi:</span>
                            <span class="badge bg-secondary">
                                @if($sponsor->position === 'home_top') Home Top
                                @elseif($sponsor->position === 'home_bottom') Home Bottom
                                @else Footer @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Urutan:</span>
                            <span class="small">{{ $sponsor->sort_order }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('logoPreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 100px; max-width: 200px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <div class="text-muted">
                <i class="fas fa-upload fs-1 mb-2"></i>
                <p class="mb-0">Upload logo baru untuk preview</p>
            </div>
        `;
    }
});

// Validate end date
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && endDate < startDate) {
        alert('Tanggal berakhir tidak boleh lebih awal dari tanggal mulai');
        this.value = '';
    }
});
</script>
@endsection
