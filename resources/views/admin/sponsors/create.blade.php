@extends('layouts.admin')

@section('title', 'Tambah Sponsor')
@section('page-title', 'Tambah Sponsor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sponsors.index') }}">Manajemen Sponsor</a></li>
    <li class="breadcrumb-item active">Tambah Sponsor</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Sponsor</h3>
            </div>
            <div class="card-body">
                        <form id="sponsorForm" action="{{ route('admin.sponsors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Sponsor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="website_url">Website URL</label>
                                        <input type="url" class="form-control @error('website_url') is-invalid @enderror"
                                               id="website_url" name="website_url" value="{{ old('website_url') }}"
                                               placeholder="https://example.com">
                                        @error('website_url')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="logo">Logo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror"
                                               id="logo" name="logo" accept="image/*" required>
                                        <label class="custom-file-label" for="logo">Pilih file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Rekomendasi: 300x150px</small>
                                @error('logo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Deskripsi singkat tentang sponsor">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Posisi Tampil <span class="text-danger">*</span></label>
                                        <select class="form-select @error('position') is-invalid @enderror"
                                                id="position" name="position" required>
                                            <option value="">Pilih Posisi</option>
                                            <option value="home_top" {{ old('position') == 'home_top' ? 'selected' : '' }}>
                                                Home - Atas Form Pencarian
                                            </option>
                                            <option value="home_bottom" {{ old('position') == 'home_bottom' ? 'selected' : '' }}>
                                                Home - Bawah Form Pencarian
                                            </option>
                                            <option value="footer" {{ old('position') == 'footer' ? 'selected' : '' }}>
                                                Footer - Semua Halaman
                                            </option>
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sort_order">Urutan Tampil</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                               min="0" placeholder="0">
                                        <small class="form-text text-muted">Semakin kecil angka, semakin awal tampil</small>
                                        @error('sort_order')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                               id="start_date" name="start_date" value="{{ old('start_date') }}">
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
                                               id="end_date" name="end_date" value="{{ old('end_date') }}">
                                        <div class="form-text">Kosongkan jika tidak ada batas waktu</div>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>

                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="sponsorForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Sponsor
                </button>
                <a href="{{ route('admin.sponsors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Preview Logo</h3>
            </div>
            <div class="card-body text-center">
                <div id="logoPreview" class="mb-3" style="min-height: 100px; display: flex; align-items: center; justify-content: center; border: 2px dashed #dee2e6; border-radius: 8px;">
                    <div class="text-muted">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <p class="mb-0">Upload logo untuk preview</p>
                    </div>
                </div>
                <small class="text-muted">Logo akan tampil seperti ini di website</small>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Tips</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success mr-2"></i>Gunakan logo dengan background transparan</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Resolusi minimal 300x150px</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Format PNG untuk hasil terbaik</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Ukuran file maksimal 2MB</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Logo preview
    $('#logo').on('change', function(e) {
        const file = e.target.files[0];
        const preview = $('#logoPreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.html(`<img src="${e.target.result}" class="img-fluid" style="max-height: 100px; max-width: 200px;">`);
            };
            reader.readAsDataURL(file);
        } else {
            preview.html(`
                <div class="text-muted">
                    <i class="fas fa-image fa-3x mb-2"></i>
                    <p class="mb-0">Upload logo untuk preview</p>
                </div>
            `);
        }
    });
});
</script>
@endpush
