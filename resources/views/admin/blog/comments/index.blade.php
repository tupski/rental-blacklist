@extends('layouts.admin')

@section('title', 'Manajemen Komentar Blog - CekPenyewa.com')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Komentar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.indeks') }}">Blog</a></li>
                        <li class="breadcrumb-item active">Komentar</li>
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
                            <i class="fas fa-comments me-2"></i>
                            Daftar Komentar
                        </h3>
                        <div class="btn-group" role="group">
                            <button type="button" id="bulkApprove" class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-check me-1"></i>Setujui
                            </button>
                            <button type="button" id="bulkReject" class="btn btn-warning btn-sm" disabled>
                                <i class="fas fa-times me-1"></i>Tolak
                            </button>
                            <button type="button" id="bulkSpam" class="btn btn-danger btn-sm" disabled>
                                <i class="fas fa-ban me-1"></i>Spam
                            </button>
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
                                    <option value="pending">Pending</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="spam">Spam</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Artikel</label>
                                <select name="post_id" id="postFilter" class="form-select form-select-sm">
                                    <option value="">Semua Artikel</option>
                                    @foreach($posts as $post)
                                        <option value="{{ $post->id }}">{{ Str::limit($post->title, 40) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Pencarian</label>
                                <input type="text" name="search" id="searchInput" class="form-control form-control-sm" 
                                       placeholder="Cari komentar...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Per Halaman</label>
                                <select name="per_page" id="perPageSelect" class="form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-md-1">
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

                    <!-- Comments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Komentar</th>
                                    <th>Artikel</th>
                                    <th>Penulis</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="commentsTableBody">
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

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Komentar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan Penolakan (Opsional)</label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="3" 
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Tolak Komentar</button>
                </div>
            </form>
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
                <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
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
let selectedComments = [];

$(document).ready(function() {
    // Load initial data
    loadComments();
    
    // Filter change handlers
    $('#statusFilter, #postFilter, #perPageSelect').on('change', function() {
        currentPage = 1;
        loadComments();
    });
    
    // Search with debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadComments();
        }, 500);
    });
    
    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterForm')[0].reset();
        currentPage = 1;
        loadComments();
    });
    
    // Pagination click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const page = new URL(url).searchParams.get('page');
            if (page) {
                currentPage = parseInt(page);
                loadComments();
            }
        }
    });
    
    // Select all checkbox
    $('#selectAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.comment-checkbox').prop('checked', isChecked);
        updateSelectedComments();
    });
    
    // Individual checkbox
    $(document).on('change', '.comment-checkbox', function() {
        updateSelectedComments();
    });
    
    // Bulk actions
    $('#bulkApprove').on('click', function() {
        if (selectedComments.length > 0) {
            bulkAction('approve');
        }
    });
    
    $('#bulkReject').on('click', function() {
        if (selectedComments.length > 0) {
            $('#rejectModal').modal('show');
        }
    });
    
    $('#bulkSpam').on('click', function() {
        if (selectedComments.length > 0) {
            bulkAction('spam');
        }
    });
    
    // Reject form submission
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        const reason = $('#rejectReason').val();
        bulkAction('reject', reason);
        $('#rejectModal').modal('hide');
    });
});

function loadComments() {
    if (isLoading) return;
    
    isLoading = true;
    $('#loadingSpinner').show();
    
    const formData = {
        status: $('#statusFilter').val(),
        post_id: $('#postFilter').val(),
        search: $('#searchInput').val(),
        per_page: $('#perPageSelect').val(),
        page: currentPage
    };
    
    $.ajax({
        url: '{{ route("admin.blog.komentar.data") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            if (response.success) {
                renderComments(response.data);
                renderPagination(response.pagination);
            }
        },
        error: function(xhr) {
            console.error('Error loading comments:', xhr);
            showError('Gagal memuat data komentar');
        },
        complete: function() {
            isLoading = false;
            $('#loadingSpinner').hide();
        }
    });
}

function renderComments(comments) {
    const tbody = $('#commentsTableBody');
    tbody.empty();
    
    if (comments.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada komentar ditemukan</h5>
                    <p class="text-muted">Belum ada komentar yang sesuai dengan filter.</p>
                </td>
            </tr>
        `);
        return;
    }
    
    comments.forEach(function(comment) {
        const row = createCommentRow(comment);
        tbody.append(row);
    });
}

function createCommentRow(comment) {
    const statusBadge = getStatusBadge(comment.status);
    const authorName = comment.user ? comment.user.name : comment.guest_name;
    const authorEmail = comment.user ? comment.user.email : comment.guest_email;
    const commentDate = new Date(comment.created_at).toLocaleDateString('id-ID');
    const content = comment.content.length > 100 ? comment.content.substring(0, 100) + '...' : comment.content;
    
    return `
        <tr>
            <td>
                <div class="form-check">
                    <input class="form-check-input comment-checkbox" type="checkbox" value="${comment.id}">
                </div>
            </td>
            <td>
                <div>
                    <p class="mb-1">${content}</p>
                    <small class="text-muted">IP: ${comment.ip_address || 'N/A'}</small>
                </div>
            </td>
            <td>
                <a href="/admin/blog/${comment.post.id}/edit" class="text-decoration-none">
                    ${comment.post.title.length > 30 ? comment.post.title.substring(0, 30) + '...' : comment.post.title}
                </a>
            </td>
            <td>
                <div>
                    <strong>${authorName}</strong><br>
                    <small class="text-muted">${authorEmail}</small>
                </div>
            </td>
            <td>${statusBadge}</td>
            <td>${commentDate}</td>
            <td>
                <div class="btn-group" role="group">
                    <a href="/admin/blog/komentar/${comment.id}" class="btn btn-sm btn-outline-info" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    ${comment.status === 'pending' ? `
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="approveComment(${comment.id})" title="Setujui">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="rejectComment(${comment.id})" title="Tolak">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function getStatusBadge(status) {
    switch(status) {
        case 'approved':
            return '<span class="badge bg-success">Disetujui</span>';
        case 'pending':
            return '<span class="badge bg-warning">Pending</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Ditolak</span>';
        case 'spam':
            return '<span class="badge bg-dark">Spam</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}

function renderPagination(pagination) {
    $('#paginationInfo').html(`
        Menampilkan ${pagination.from || 0} - ${pagination.to || 0} dari ${pagination.total} komentar
    `);
    
    $('#paginationLinks').html(pagination.links);
}

function updateSelectedComments() {
    selectedComments = [];
    $('.comment-checkbox:checked').each(function() {
        selectedComments.push($(this).val());
    });
    
    const hasSelection = selectedComments.length > 0;
    $('#bulkApprove, #bulkReject, #bulkSpam').prop('disabled', !hasSelection);
    
    // Update select all checkbox
    const totalCheckboxes = $('.comment-checkbox').length;
    const checkedCheckboxes = $('.comment-checkbox:checked').length;
    
    if (checkedCheckboxes === 0) {
        $('#selectAll').prop('indeterminate', false).prop('checked', false);
    } else if (checkedCheckboxes === totalCheckboxes) {
        $('#selectAll').prop('indeterminate', false).prop('checked', true);
    } else {
        $('#selectAll').prop('indeterminate', true);
    }
}

function approveComment(commentId) {
    $.post(`/admin/blog/komentar/${commentId}/setujui`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            loadComments();
        }
    })
    .fail(function() {
        showError('Gagal menyetujui komentar');
    });
}

function rejectComment(commentId) {
    // You can implement a simple reject or show modal for reason
    $.post(`/admin/blog/komentar/${commentId}/tolak`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            loadComments();
        }
    })
    .fail(function() {
        showError('Gagal menolak komentar');
    });
}

function deleteComment(commentId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/blog/komentar/${commentId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function bulkAction(action, reason = null) {
    const data = {
        action: action,
        comment_ids: selectedComments,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    if (reason) {
        data.reason = reason;
    }
    
    $.post('{{ route("admin.blog.komentar.bulk-action") }}', data)
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            loadComments();
            selectedComments = [];
            $('#selectAll').prop('checked', false);
        }
    })
    .fail(function() {
        showError('Gagal memproses komentar');
    });
}

function showSuccess(message) {
    // You can implement toast notification here
    alert(message);
}

function showError(message) {
    // You can implement toast notification here
    alert(message);
}
</script>
@endpush
