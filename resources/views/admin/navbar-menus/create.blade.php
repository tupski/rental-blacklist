@extends('layouts.admin')

@section('title', 'Tambah Menu Navbar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Menu Navbar
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menu-navbar.indeks') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.menu-navbar.simpan') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Menu <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- URL Type Selection -->
                                <div class="mb-3">
                                    <label class="form-label">Tipe URL <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="url_type" id="url_type_custom" value="custom" checked>
                                        <label class="form-check-label" for="url_type_custom">
                                            URL Custom
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="url_type" id="url_type_route" value="route">
                                        <label class="form-check-label" for="url_type_route">
                                            Route Laravel
                                        </label>
                                    </div>
                                </div>

                                <!-- Custom URL -->
                                <div class="mb-3" id="custom_url_group">
                                    <label for="url" class="form-label">URL</label>
                                    <input type="text" 
                                           class="form-control @error('url') is-invalid @enderror" 
                                           id="url" 
                                           name="url" 
                                           value="{{ old('url') }}"
                                           placeholder="https://example.com atau /halaman">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Route Name -->
                                <div class="mb-3" id="route_name_group" style="display: none;">
                                    <label for="route_name" class="form-label">Nama Route</label>
                                    <select class="form-select @error('route_name') is-invalid @enderror" 
                                            id="route_name" 
                                            name="route_name">
                                        <option value="">Pilih Route</option>
                                        <option value="beranda" {{ old('route_name') === 'beranda' ? 'selected' : '' }}>beranda (Beranda)</option>
                                        <option value="laporan.buat" {{ old('route_name') === 'laporan.buat' ? 'selected' : '' }}>laporan.buat (Lapor)</option>
                                        <option value="sponsor.indeks" {{ old('route_name') === 'sponsor.indeks' ? 'selected' : '' }}>sponsor.indeks (Sponsor)</option>
                                        <option value="verifikasi.index" {{ old('route_name') === 'verifikasi.index' ? 'selected' : '' }}>verifikasi.index (Verifikasi)</option>
                                        <option value="blog.indeks" {{ old('route_name') === 'blog.indeks' ? 'selected' : '' }}>blog.indeks (Blog)</option>
                                        <option value="dasbor" {{ old('route_name') === 'dasbor' ? 'selected' : '' }}>dasbor (Dashboard)</option>
                                    </select>
                                    @error('route_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Route Parameters -->
                                <div class="mb-3" id="route_params_group" style="display: none;">
                                    <label for="route_params" class="form-label">Parameter Route (JSON)</label>
                                    <textarea class="form-control @error('route_params') is-invalid @enderror" 
                                              id="route_params" 
                                              name="route_params" 
                                              rows="3"
                                              placeholder='{"id": 1, "slug": "example"}'>{{ old('route_params') }}</textarea>
                                    @error('route_params')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format JSON untuk parameter route. Kosongkan jika tidak ada parameter.
                                    </small>
                                </div>

                                <!-- Icon -->
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icon (Font Awesome)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i id="icon-preview" class="fas fa-link"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" 
                                               name="icon" 
                                               value="{{ old('icon', 'fas fa-link') }}"
                                               placeholder="fas fa-home">
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Gunakan class Font Awesome, contoh: fas fa-home, fas fa-user, dll.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Pengaturan Menu</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Order -->
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Urutan</label>
                                            <input type="number" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   id="order" 
                                                   name="order" 
                                                   value="{{ old('order', 0) }}" 
                                                   min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Angka lebih kecil akan tampil lebih dulu.
                                            </small>
                                        </div>

                                        <!-- Visibility -->
                                        <div class="mb-3">
                                            <label for="visibility" class="form-label">Visibilitas</label>
                                            <select class="form-select @error('visibility') is-invalid @enderror" 
                                                    id="visibility" 
                                                    name="visibility" 
                                                    required>
                                                <option value="all" {{ old('visibility') === 'all' ? 'selected' : '' }}>Semua User</option>
                                                <option value="guest" {{ old('visibility') === 'guest' ? 'selected' : '' }}>Guest Saja</option>
                                                <option value="auth" {{ old('visibility') === 'auth' ? 'selected' : '' }}>User Login</option>
                                                <option value="admin" {{ old('visibility') === 'admin' ? 'selected' : '' }}>Admin Saja</option>
                                                <option value="rental" {{ old('visibility') === 'rental' ? 'selected' : '' }}>Pemilik Rental</option>
                                            </select>
                                            @error('visibility')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Parent Menu -->
                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Menu Induk</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                                    id="parent_id" 
                                                    name="parent_id">
                                                <option value="">Tidak ada (Menu Utama)</option>
                                                @foreach($parentMenus as $parentMenu)
                                                    <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                                                        {{ $parentMenu->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Checkboxes -->
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Aktif
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="open_new_tab" 
                                                   name="open_new_tab" 
                                                   value="1" 
                                                   {{ old('open_new_tab') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="open_new_tab">
                                                Buka di Tab Baru
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.menu-navbar.indeks') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan Menu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // URL Type toggle
    $('input[name="url_type"]').change(function() {
        if ($(this).val() === 'custom') {
            $('#custom_url_group').show();
            $('#route_name_group, #route_params_group').hide();
            $('#url').attr('required', true);
            $('#route_name').attr('required', false);
        } else {
            $('#custom_url_group').hide();
            $('#route_name_group').show();
            $('#url').attr('required', false);
            $('#route_name').attr('required', true);
        }
    });

    // Show route params when route is selected
    $('#route_name').change(function() {
        if ($(this).val()) {
            $('#route_params_group').show();
        } else {
            $('#route_params_group').hide();
        }
    });

    // Icon preview
    $('#icon').on('input', function() {
        const iconClass = $(this).val() || 'fas fa-link';
        $('#icon-preview').attr('class', iconClass);
    });

    // Trigger initial state
    $('input[name="url_type"]:checked').trigger('change');
    $('#route_name').trigger('change');
});
</script>
@endpush
