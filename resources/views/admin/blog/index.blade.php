@extends('layouts.admin')

@section('title', 'Kelola Blog')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-blog me-2"></i>
                        Kelola Blog
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter & Search -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Artikel
                        </h3>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.blog.buat') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Tulis Artikel
                            </a>
                            <a href="{{ route('admin.blog.kategori.indeks') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-folder me-1"></i>
                                Kategori
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form id="filterForm" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label small">Status</label>
                                <select name="status" id="statusFilter" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="scheduled">Scheduled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Kategori</label>
                                <select name="category" id="categoryFilter" class="form-select form-select-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Pencarian</label>
                                <input type="text" name="search" id="searchInput" class="form-control form-control-sm"
                                       placeholder="Cari artikel...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Per Halaman</label>
                                <select name="per_page" id="perPageSelect" class="form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                            </div>
                            <div class="col-md-1">
                                <div id="loadingSpinner" class="text-center" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Posts Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Artikel</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Penulis</th>
                                    <th>Views</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="postsTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Info -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="paginationInfo" class="text-muted small">
                            <!-- Pagination info will be loaded via AJAX -->
                        </div>
                        <div id="paginationLinks">
                            <!-- Pagination links will be loaded via AJAX -->
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
                <p>Apakah Anda yakin ingin menghapus artikel ini?</p>
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
let currentPage = 1;
let isLoading = false;

$(document).ready(function() {
    // Load initial data
    loadPosts();

    // Filter change handlers
    $('#statusFilter, #categoryFilter, #perPageSelect').on('change', function() {
        currentPage = 1;
        loadPosts();
    });

    // Search with debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadPosts();
        }, 500);
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterForm')[0].reset();
        currentPage = 1;
        loadPosts();
    });

    // Pagination click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const page = new URL(url).searchParams.get('page');
            if (page) {
                currentPage = parseInt(page);
                loadPosts();
            }
        }
    });
});

function loadPosts() {
    if (isLoading) return;

    isLoading = true;
    $('#loadingSpinner').show();

    const formData = {
        status: $('#statusFilter').val(),
        category: $('#categoryFilter').val(),
        search: $('#searchInput').val(),
        per_page: $('#perPageSelect').val(),
        page: currentPage
    };

    $.ajax({
        url: '{{ route("admin.blog.data") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            if (response.success) {
                renderPosts(response.data);
                renderPagination(response.pagination);
            }
        },
        error: function(xhr) {
            console.error('Error loading posts:', xhr);
            showError('Gagal memuat data artikel');
        },
        complete: function() {
            isLoading = false;
            $('#loadingSpinner').hide();
        }
    });
}

function renderPosts(posts) {
    const tbody = $('#postsTableBody');
    tbody.empty();

    if (posts.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada artikel ditemukan</h5>
                    <p class="text-muted">Coba ubah filter atau buat artikel baru.</p>
                    <a href="{{ route('admin.blog.buat') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tulis Artikel Baru
                    </a>
                </td>
            </tr>
        `);
        return;
    }

    posts.forEach(function(post) {
        const row = createPostRow(post);
        tbody.append(row);
    });
}

function createPostRow(post) {
    const featuredImage = post.featured_image ?
        `<img src="/storage/${post.featured_image}" class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;" alt="${post.title}">` : '';

    const seoScore = post.seo_score ?
        `<div class="mt-1">
            <span class="badge ${post.seo_score >= 80 ? 'bg-success' : (post.seo_score >= 60 ? 'bg-warning' : 'bg-danger')}">
                SEO: ${post.seo_score}%
            </span>
        </div>` : '';

    const statusBadge = getStatusBadge(post.status);
    const publishedDate = post.published_at ? new Date(post.published_at).toLocaleDateString('id-ID') : '-';

    const viewButton = post.status === 'published' ?
        `<a href="/blog/${post.category.slug}/${post.slug}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat">
            <i class="fas fa-eye"></i>
        </a>` : '';

    return `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    ${featuredImage}
                    <div>
                        <h6 class="mb-0">
                            <a href="/admin/blog/${post.id}/edit" class="text-decoration-none">
                                ${post.title}
                            </a>
                        </h6>
                        <small class="text-muted">
                            ${post.excerpt ? post.excerpt.substring(0, 60) + '...' : ''}
                        </small>
                        ${seoScore}
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-primary">${post.category.name}</span>
            </td>
            <td>${statusBadge}</td>
            <td>${post.author.name}</td>
            <td>
                <i class="fas fa-eye text-muted me-1"></i>
                ${post.views_count.toLocaleString()}
            </td>
            <td>${publishedDate}</td>
            <td>
                <div class="btn-group" role="group">
                    ${viewButton}
                    <a href="/admin/blog/${post.id}/edit" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePost(${post.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function getStatusBadge(status) {
    switch(status) {
        case 'published':
            return '<span class="badge bg-success">Published</span>';
        case 'draft':
            return '<span class="badge bg-secondary">Draft</span>';
        case 'scheduled':
            return '<span class="badge bg-info">Scheduled</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}

function renderPagination(pagination) {
    $('#paginationInfo').html(`
        Menampilkan ${pagination.from || 0} - ${pagination.to || 0} dari ${pagination.total} artikel
    `);

    $('#paginationLinks').html(pagination.links);
}

function deletePost(postId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/blog/${postId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function showError(message) {
    // You can implement a toast notification here
    alert(message);
}
</script>
@endpush
