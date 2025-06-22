@extends('layouts.admin')

@section('title', 'Tambah Paket Sponsor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Paket Sponsor
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.paket-sponsor.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.paket-sponsor.simpan') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Nama Paket -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Paket <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_days">Masa Berlaku (Hari) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_days') is-invalid @enderror"
                                       id="duration_days" name="duration_days" value="{{ old('duration_days') }}" min="1" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Contoh: 30 hari = 1 bulan, 365 hari = 1 tahun</small>
                            </div>
                        </div>

                        <!-- Urutan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">Urutan Tampil</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Semakin kecil angka, semakin atas posisinya</small>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Deskripsi Paket</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Benefits -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Keuntungan yang Didapat <span class="text-danger">*</span></label>
                                <div id="benefits-container">
                                    @if(old('benefits'))
                                        @foreach(old('benefits') as $index => $benefit)
                                            <div class="input-group mb-2 benefit-item">
                                                <input type="text" class="form-control @error('benefits.'.$index) is-invalid @enderror"
                                                       name="benefits[]" value="{{ $benefit }}" placeholder="Masukkan keuntungan...">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger remove-benefit">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                @error('benefits.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2 benefit-item">
                                            <input type="text" class="form-control" name="benefits[]" placeholder="Masukkan keuntungan...">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-benefit">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-benefit">
                                    <i class="fas fa-plus"></i> Tambah Keuntungan
                                </button>
                                @error('benefits')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Opsi Penempatan -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Opsi Penempatan <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="placement_home_top"
                                                   name="placement_options[]" value="home_top"
                                                   {{ in_array('home_top', old('placement_options', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="placement_home_top">
                                                Beranda Atas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="placement_home_bottom"
                                                   name="placement_options[]" value="home_bottom"
                                                   {{ in_array('home_bottom', old('placement_options', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="placement_home_bottom">
                                                Beranda Bawah
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="placement_footer"
                                                   name="placement_options[]" value="footer"
                                                   {{ in_array('footer', old('placement_options', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="placement_footer">
                                                Footer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="placement_sidebar"
                                                   name="placement_options[]" value="sidebar"
                                                   {{ in_array('sidebar', old('placement_options', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="placement_sidebar">
                                                Sidebar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('placement_options')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pengaturan Logo -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_logo_size_kb">Maksimal Ukuran Logo (KB)</label>
                                <input type="number" class="form-control @error('max_logo_size_kb') is-invalid @enderror"
                                       id="max_logo_size_kb" name="max_logo_size_kb" value="{{ old('max_logo_size_kb', 2048) }}" min="100" max="10240">
                                @error('max_logo_size_kb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recommended_logo_size">Ukuran Logo yang Direkomendasikan</label>
                                <input type="text" class="form-control @error('recommended_logo_size') is-invalid @enderror"
                                       id="recommended_logo_size" name="recommended_logo_size" value="{{ old('recommended_logo_size', '300x150') }}"
                                       placeholder="300x150">
                                @error('recommended_logo_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status dan Badge -->
                        <div class="col-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_popular"
                                                   name="is_popular" {{ old('is_popular') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_popular">
                                                <i class="fas fa-star text-warning"></i> Badge Populer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_active"
                                                   name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">
                                                <i class="fas fa-toggle-on text-success"></i> Status Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Paket
                    </button>
                    <a href="{{ route('admin.paket-sponsor.indeks') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Add benefit
    $('#add-benefit').click(function() {
        const benefitHtml = `
            <div class="input-group mb-2 benefit-item">
                <input type="text" class="form-control" name="benefits[]" placeholder="Masukkan keuntungan...">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-benefit">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        $('#benefits-container').append(benefitHtml);
    });

    // Remove benefit
    $(document).on('click', '.remove-benefit', function() {
        if ($('.benefit-item').length > 1) {
            $(this).closest('.benefit-item').remove();
        } else {
            alert('Minimal harus ada satu keuntungan!');
        }
    });
});
</script>
@endpush
@endsection
