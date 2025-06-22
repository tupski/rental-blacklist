@extends('layouts.admin')

@section('title', 'Edit Halaman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Halaman: {{ $page->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.halaman.indeks') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                        <a href="{{ route('halaman.tampil', $page->slug) }}" 
                           target="_blank" 
                           class="btn btn-info">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Lihat Halaman
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.halaman.perbarui', $page) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Halaman <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $page->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ url('/') }}/</span>
                                        <input type="text" 
                                               class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" 
                                               name="slug" 
                                               value="{{ old('slug', $page->slug) }}">
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="15" 
                                              required>{{ old('content', $page->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Ringkasan</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" 
                                              name="excerpt" 
                                              rows="3" 
                                              placeholder="Ringkasan singkat halaman (opsional)">{{ old('excerpt', $page->excerpt) }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Status -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Pengaturan Publikasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status" 
                                                    required>
                                                <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="show_in_menu" 
                                                   name="show_in_menu" 
                                                   value="1" 
                                                   {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_in_menu">
                                                Tampilkan di Menu Navigasi
                                            </label>
                                        </div>

                                        <div class="mb-3 mt-3" id="menu_order_group" style="{{ old('show_in_menu', $page->show_in_menu) ? 'display: block;' : 'display: none;' }}">
                                            <label for="menu_order" class="form-label">Urutan Menu</label>
                                            <input type="number" 
                                                   class="form-control @error('menu_order') is-invalid @enderror" 
                                                   id="menu_order" 
                                                   name="menu_order" 
                                                   value="{{ old('menu_order', $page->menu_order) }}" 
                                                   min="0">
                                            @error('menu_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Angka lebih kecil akan tampil lebih dulu.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                                   id="meta_title" 
                                                   name="meta_title" 
                                                   value="{{ old('meta_title', $page->meta_title) }}"
                                                   maxlength="60">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Maksimal 60 karakter</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description" 
                                                      name="meta_description" 
                                                      rows="3" 
                                                      maxlength="160">{{ old('meta_description', $page->meta_description) }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Maksimal 160 karakter</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                   id="meta_keywords" 
                                                   name="meta_keywords" 
                                                   value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                                   placeholder="keyword1, keyword2, keyword3">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Pisahkan dengan koma</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong> {{ $page->created_at->format('d/m/Y H:i') }}<br>
                                            <strong>Oleh:</strong> {{ $page->creator->name }}<br>
                                            <strong>Terakhir diubah:</strong> {{ $page->updated_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.halaman.indeks') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <div>
                                <button type="submit" name="status" value="draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan sebagai Draft
                                </button>
                                <button type="submit" name="status" value="published" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Publikasikan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Show/hide menu order field
    $('#show_in_menu').change(function() {
        if ($(this).is(':checked')) {
            $('#menu_order_group').show();
        } else {
            $('#menu_order_group').hide();
        }
    });
});
</script>
@endpush
