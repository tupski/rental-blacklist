@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-plus me-2"></i>
                        Tulis Artikel Baru
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.indeks') }}">Blog</a></li>
                        <li class="breadcrumb-item active">Tulis Baru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form id="blogForm" action="{{ route('admin.blog.simpan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <!-- Basic Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-edit me-2"></i>
                                    Konten Artikel
                                </h3>
                                <div class="card-tools">
                                    <span id="autoSaveStatus" class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i>
                                        Belum disimpan
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           required
                                           placeholder="Masukkan judul artikel...">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ url('/blog/kategori/') }}/</span>
                                        <input type="text"
                                               class="form-control @error('slug') is-invalid @enderror"
                                               id="slug"
                                               name="slug"
                                               value="{{ old('slug') }}"
                                               placeholder="slug-artikel">
                                        <button type="button" class="btn btn-outline-secondary" onclick="generateSlug()">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan untuk generate otomatis dari judul</small>
                                    @error('slug')
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
                                              placeholder="Ringkasan singkat artikel (opsional)">{{ old('excerpt') }}</textarea>
                                    <small class="form-text text-muted">Maksimal 500 karakter</small>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content Editor -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten Artikel <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content"
                                              name="content"
                                              required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-search me-2"></i>
                                    Pengaturan SEO
                                </h3>
                                <div class="card-tools">
                                    <div id="seoScore" class="badge bg-secondary">
                                        <i class="fas fa-chart-line me-1"></i>
                                        SEO Score: 0%
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- SEO Title -->
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label">SEO Title</label>
                                    <input type="text"
                                           class="form-control @error('seo_title') is-invalid @enderror"
                                           id="seo_title"
                                           name="seo_title"
                                           value="{{ old('seo_title') }}"
                                           maxlength="60"
                                           placeholder="Judul untuk mesin pencari (maksimal 60 karakter)">
                                    <div class="d-flex justify-content-between">
                                        <small class="form-text text-muted">Kosongkan untuk menggunakan judul artikel</small>
                                        <small id="seoTitleCount" class="form-text text-muted">0/60</small>
                                    </div>
                                    @error('seo_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SEO Description -->
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('seo_description') is-invalid @enderror"
                                              id="seo_description"
                                              name="seo_description"
                                              rows="3"
                                              maxlength="160"
                                              placeholder="Deskripsi untuk mesin pencari (maksimal 160 karakter)">{{ old('seo_description') }}</textarea>
                                    <div class="d-flex justify-content-between">
                                        <small class="form-text text-muted">Deskripsi yang muncul di hasil pencarian</small>
                                        <small id="seoDescCount" class="form-text text-muted">0/160</small>
                                    </div>
                                    @error('seo_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SEO Keywords -->
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label">Keywords</label>
                                    <input type="text"
                                           class="form-control @error('seo_keywords') is-invalid @enderror"
                                           id="seo_keywords"
                                           name="seo_keywords"
                                           value="{{ old('seo_keywords') }}"
                                           placeholder="kata kunci, dipisahkan, dengan koma">
                                    <small class="form-text text-muted">Pisahkan dengan koma (contoh: rental mobil, blacklist, keamanan)</small>
                                    @error('seo_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Canonical URL -->
                                <div class="mb-3">
                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                    <input type="url"
                                           class="form-control @error('canonical_url') is-invalid @enderror"
                                           id="canonical_url"
                                           name="canonical_url"
                                           value="{{ old('canonical_url') }}"
                                           placeholder="https://example.com/artikel-asli">
                                    <small class="form-text text-muted">URL asli jika artikel ini adalah duplikasi</small>
                                    @error('canonical_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SEO Analysis -->
                                <div id="seoAnalysis" class="mt-3" style="display: none;">
                                    <h6>Analisis SEO:</h6>
                                    <div id="seoIssues"></div>
                                    <div id="seoSuggestions"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Publish Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-cog me-2"></i>
                                    Pengaturan Publikasi
                                </h3>
                            </div>
                            <div class="card-body">
                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status"
                                            required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publish</option>
                                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Jadwalkan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3" id="publishedAtGroup" style="display: none;">
                                    <label for="published_at" class="form-label">Tanggal Publikasi</label>
                                    <input type="datetime-local"
                                           class="form-control @error('published_at') is-invalid @enderror"
                                           id="published_at"
                                           name="published_at"
                                           value="{{ old('published_at') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan Artikel
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                        <i class="fas fa-file-alt me-2"></i>
                                        Simpan sebagai Draft
                                    </button>
                                    <a href="{{ route('admin.blog.indeks') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times me-2"></i>
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-image me-2"></i>
                                    Gambar Utama
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file"
                                           class="form-control @error('featured_image') is-invalid @enderror"
                                           id="featured_image"
                                           name="featured_image"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" style="display: none;">
                                    <img id="preview" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Comments Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-comments me-2"></i>
                                    Pengaturan Komentar
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="comments_enabled"
                                               name="comments_enabled" value="1"
                                               {{ old('comments_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="comments_enabled">
                                            Aktifkan Komentar
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Izinkan pembaca untuk memberikan komentar pada artikel ini</small>
                                </div>

                                <div class="mb-0" id="comment_approval_setting">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="comments_require_approval"
                                               name="comments_require_approval" value="1"
                                               {{ old('comments_require_approval', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="comments_require_approval">
                                            Komentar Perlu Persetujuan
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Komentar akan ditampilkan setelah disetujui oleh admin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.tox-tinymce {
    border-radius: 0.375rem !important;
}

.seo-issue {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
    border-left: 4px solid #dc3545;
    background-color: #f8d7da;
    color: #721c24;
}

.seo-suggestion {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
    border-left: 4px solid #0d6efd;
    background-color: #cff4fc;
    color: #055160;
}

.char-count {
    font-size: 0.875rem;
}

.char-count.warning {
    color: #f57c00;
}

.char-count.danger {
    color: #d32f2f;
}
</style>
@endpush

@push('scripts')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
let hasUnsavedChanges = false;
let autoSaveInterval;

$(document).ready(function() {
    // Initialize TinyMCE
    initTinyMCE();

    // Initialize form handlers
    initFormHandlers();

    // Initialize auto-save
    initAutoSave();

    // Initialize SEO analysis
    initSeoAnalysis();

    // Initialize character counters
    initCharCounters();

    // Initialize comment settings
    initCommentSettings();

    // Warn before leaving page with unsaved changes
    initUnsavedChangesWarning();
});

function initTinyMCE() {
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media table | code preview fullscreen | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; line-height: 1.6; }',
        image_advtab: true,
        image_uploadtab: true,
        file_picker_types: 'image',
        automatic_uploads: true,
        images_upload_url: '{{ route("admin.blog.upload-image") }}',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.blog.upload-image") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },
        setup: function(editor) {
            editor.on('change', function() {
                hasUnsavedChanges = true;
                updateAutoSaveStatus('Belum disimpan', 'secondary');

                // Trigger SEO analysis
                setTimeout(analyzeSeo, 1000);
            });
        }
    });
}

function initFormHandlers() {
    // Title to slug generation
    $('#title').on('input', function() {
        if (!$('#slug').val()) {
            generateSlug();
        }
        hasUnsavedChanges = true;
        setTimeout(analyzeSeo, 500);
    });

    // Status change handler
    $('#status').on('change', function() {
        if ($(this).val() === 'scheduled') {
            $('#publishedAtGroup').show();
        } else {
            $('#publishedAtGroup').hide();
        }
    });

    // Form submission
    $('#blogForm').on('submit', function() {
        hasUnsavedChanges = false;
        $(window).off('beforeunload');
    });
}

function initAutoSave() {
    autoSaveInterval = setInterval(function() {
        if (hasUnsavedChanges && $('#title').val()) {
            autoSave();
        }
    }, 30000); // Auto-save every 30 seconds
}

function initSeoAnalysis() {
    $('#title, #seo_title, #seo_description, #seo_keywords').on('input', function() {
        setTimeout(analyzeSeo, 500);
    });
}

function initCharCounters() {
    // SEO Title counter
    $('#seo_title').on('input', function() {
        const count = $(this).val().length;
        const counter = $('#seoTitleCount');
        counter.text(count + '/60');

        if (count > 60) {
            counter.addClass('text-danger').removeClass('text-warning text-muted');
        } else if (count > 50) {
            counter.addClass('text-warning').removeClass('text-danger text-muted');
        } else {
            counter.addClass('text-muted').removeClass('text-danger text-warning');
        }
    });

    // SEO Description counter
    $('#seo_description').on('input', function() {
        const count = $(this).val().length;
        const counter = $('#seoDescCount');
        counter.text(count + '/160');

        if (count > 160) {
            counter.addClass('text-danger').removeClass('text-warning text-muted');
        } else if (count > 140) {
            counter.addClass('text-warning').removeClass('text-danger text-muted');
        } else {
            counter.addClass('text-muted').removeClass('text-danger text-warning');
        }
    });
}

function initCommentSettings() {
    // Toggle comment approval setting based on comments enabled
    $('#comments_enabled').on('change', function() {
        if ($(this).is(':checked')) {
            $('#comment_approval_setting').show();
        } else {
            $('#comment_approval_setting').hide();
        }
    });

    // Initialize visibility on page load
    if (!$('#comments_enabled').is(':checked')) {
        $('#comment_approval_setting').hide();
    }
}

function initUnsavedChangesWarning() {
    $(window).on('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            const message = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
            e.returnValue = message;
            return message;
        }
    });

    // Remove warning when navigating to safe pages
    $('a[href*="admin/blog"]').on('click', function() {
        if (hasUnsavedChanges) {
            return confirm('Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?');
        }
    });
}

function generateSlug() {
    const title = $('#title').val();
    if (!title) return;

    $.post('{{ route("admin.blog.generate-slug") }}', {
        title: title,
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        $('#slug').val(response.slug);
    })
    .fail(function() {
        // Fallback to client-side generation
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
    });
}

function autoSave() {
    const formData = {
        title: $('#title').val(),
        content: tinymce.get('content').getContent(),
        excerpt: $('#excerpt').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    updateAutoSaveStatus('Menyimpan...', 'warning');

    $.post('{{ route("admin.blog.auto-save", ["post" => 0]) }}', formData)
    .done(function(response) {
        hasUnsavedChanges = false;
        updateAutoSaveStatus('Tersimpan ' + response.saved_at, 'success');
    })
    .fail(function() {
        updateAutoSaveStatus('Gagal menyimpan', 'danger');
    });
}

function saveDraft() {
    $('#status').val('draft');
    $('#blogForm').submit();
}

function analyzeSeo() {
    const data = {
        title: $('#title').val(),
        content: tinymce.get('content') ? tinymce.get('content').getContent() : '',
        seo_description: $('#seo_description').val(),
        seo_keywords: $('#seo_keywords').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (!data.title || !data.content) return;

    $.post('{{ route("admin.blog.analyze-seo") }}', data)
    .done(function(response) {
        updateSeoScore(response.score);
        displaySeoAnalysis(response);
    });
}

function updateSeoScore(score) {
    const badge = $('#seoScore');
    badge.html('<i class="fas fa-chart-line me-1"></i>SEO Score: ' + score + '%');

    if (score >= 80) {
        badge.removeClass('bg-secondary bg-warning bg-danger').addClass('bg-success');
    } else if (score >= 60) {
        badge.removeClass('bg-secondary bg-success bg-danger').addClass('bg-warning');
    } else {
        badge.removeClass('bg-secondary bg-success bg-warning').addClass('bg-danger');
    }
}

function displaySeoAnalysis(analysis) {
    const analysisDiv = $('#seoAnalysis');
    const issuesDiv = $('#seoIssues');
    const suggestionsDiv = $('#seoSuggestions');

    // Clear previous analysis
    issuesDiv.empty();
    suggestionsDiv.empty();

    // Display issues
    if (analysis.issues && analysis.issues.length > 0) {
        analysis.issues.forEach(function(issue) {
            issuesDiv.append('<div class="seo-issue"><i class="fas fa-exclamation-triangle me-2"></i>' + issue + '</div>');
        });
    }

    // Display suggestions
    if (analysis.suggestions && analysis.suggestions.length > 0) {
        analysis.suggestions.forEach(function(suggestion) {
            suggestionsDiv.append('<div class="seo-suggestion"><i class="fas fa-lightbulb me-2"></i>' + suggestion + '</div>');
        });
    }

    // Show analysis if there are issues or suggestions
    if ((analysis.issues && analysis.issues.length > 0) || (analysis.suggestions && analysis.suggestions.length > 0)) {
        analysisDiv.show();
    } else {
        analysisDiv.hide();
    }
}

function updateAutoSaveStatus(text, type) {
    const status = $('#autoSaveStatus');
    status.removeClass('bg-secondary bg-success bg-warning bg-danger').addClass('bg-' + type);
    status.html('<i class="fas fa-clock me-1"></i>' + text);
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Cleanup on page unload
$(window).on('unload', function() {
    if (autoSaveInterval) {
        clearInterval(autoSaveInterval);
    }
});
</script>
@endpush
