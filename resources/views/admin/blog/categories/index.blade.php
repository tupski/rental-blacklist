@extends('layouts.admin')

@section('title', 'Kelola Kategori Blog')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-folder me-2"></i>
                        Kelola Kategori Blog
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.indeks') }}">Blog</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Categories List -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list me-2"></i>
                                Daftar Kategori
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($categories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Slug</th>
                                            <th>Artikel</th>
                                            <th>Status</th>
                                            <th>Urutan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoriesTable">
                                        @foreach($categories as $category)
                                        <tr data-id="{{ $category->id }}">
                                            <td>
                                                <div>
                                                    <strong>{{ $category->name }}</strong>
                                                    @if($category->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($category->description, 60) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $category->slug }}</code>
                                                <br>
                                                <small class="text-muted">
                                                    <a href="{{ route('blog.kategori', $category->slug) }}" target="_blank" class="text-decoration-none">
                                                        <i class="fas fa-external-link-alt me-1"></i>Lihat
                                                    </a>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->posts_count }} artikel</span>
                                            </td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $category->sort_order }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.blog.kategori.edit', $category) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteCategory({{ $category->id }})" 
                                                            title="Hapus"
                                                            {{ $category->posts_count > 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada kategori</h5>
                                <p class="text-muted">Buat kategori pertama untuk mengorganisir artikel blog Anda.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Category Form -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Kategori Baru
                            </h3>
                        </div>
                        <div class="card-body">
                            <form id="categoryForm" action="{{ route('admin.blog.kategori.simpan') }}" method="POST">
                                @csrf
                                <input type="hidden" id="categoryId" name="category_id" value="">
                                <input type="hidden" id="formMethod" name="_method" value="">

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required
                                           placeholder="Masukkan nama kategori...">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug URL</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" 
                                               name="slug" 
                                               value="{{ old('slug') }}"
                                               placeholder="slug-kategori">
                                        <button type="button" class="btn btn-outline-secondary" onclick="generateSlug()">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan untuk generate otomatis dari nama</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SEO Meta Title -->
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" 
                                           class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title') }}"
                                           maxlength="60"
                                           placeholder="SEO title untuk kategori">
                                    <small class="form-text text-muted">Maksimal 60 karakter</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SEO Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" 
                                              name="meta_description" 
                                              rows="2"
                                              maxlength="160"
                                              placeholder="SEO description untuk kategori">{{ old('meta_description') }}</textarea>
                                    <small class="form-text text-muted">Maksimal 160 karakter</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Urutan</label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', 0) }}"
                                           min="0"
                                           placeholder="0">
                                    <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu</small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Kategori aktif akan tampil di website</small>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan Kategori
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()" id="cancelBtn" style="display: none;">
                                        <i class="fas fa-times me-2"></i>
                                        Batal Edit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
                <p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateSlug() {
    const name = $('#name').val();
    if (!name) return;
    
    const categoryId = $('#categoryId').val();
    
    $.post('{{ route("admin.blog.kategori.generate-slug") }}', {
        name: name,
        category_id: categoryId || null,
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        $('#slug').val(response.slug);
    })
    .fail(function() {
        // Fallback to client-side generation
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
    });
}

function editCategory(category) {
    // Populate form with category data
    $('#categoryId').val(category.id);
    $('#name').val(category.name);
    $('#slug').val(category.slug);
    $('#description').val(category.description || '');
    $('#meta_title').val(category.meta_title || '');
    $('#meta_description').val(category.meta_description || '');
    $('#sort_order').val(category.sort_order || 0);
    $('#is_active').prop('checked', category.is_active);
    
    // Update form action and method
    $('#categoryForm').attr('action', `/admin/blog/kategori/${category.id}`);
    $('#formMethod').val('PUT');
    
    // Update UI
    $('.card-title').html('<i class="fas fa-edit me-2"></i>Edit Kategori');
    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Perbarui Kategori');
    $('#cancelBtn').show();
    
    // Scroll to form
    $('html, body').animate({
        scrollTop: $('#categoryForm').offset().top - 100
    }, 500);
}

function resetForm() {
    // Reset form
    $('#categoryForm')[0].reset();
    $('#categoryId').val('');
    $('#formMethod').val('');
    
    // Reset form action
    $('#categoryForm').attr('action', '{{ route("admin.blog.kategori.simpan") }}');
    
    // Update UI
    $('.card-title').html('<i class="fas fa-plus me-2"></i>Tambah Kategori Baru');
    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Simpan Kategori');
    $('#cancelBtn').hide();
    
    // Set default values
    $('#is_active').prop('checked', true);
    $('#sort_order').val(0);
}

function deleteCategory(categoryId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/blog/kategori/${categoryId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto-generate slug when name changes
$('#name').on('input', function() {
    if (!$('#slug').val() || $('#categoryId').val() === '') {
        generateSlug();
    }
});

// Character counters
$('#meta_title').on('input', function() {
    const count = $(this).val().length;
    const maxLength = 60;
    if (count > maxLength) {
        $(this).addClass('is-invalid');
    } else {
        $(this).removeClass('is-invalid');
    }
});

$('#meta_description').on('input', function() {
    const count = $(this).val().length;
    const maxLength = 160;
    if (count > maxLength) {
        $(this).addClass('is-invalid');
    } else {
        $(this).removeClass('is-invalid');
    }
});

// Make table rows clickable for editing
$(document).on('click', 'tbody tr', function() {
    const categoryId = $(this).data('id');
    if (categoryId) {
        // Get category data from the row
        const category = {
            id: categoryId,
            name: $(this).find('td:first strong').text(),
            slug: $(this).find('code').text(),
            description: $(this).find('small.text-muted').text() || '',
            is_active: $(this).find('.badge').hasClass('bg-success'),
            sort_order: $(this).find('.badge.bg-info').text()
        };
        
        editCategory(category);
    }
});
</script>
@endpush
