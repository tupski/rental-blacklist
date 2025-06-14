@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@push('styles')
<style>
    .table-row-hover:hover {
        background-color: #f8f9fa !important;
    }
    .form-control {
        border: 2px solid #dee2e6;
        color: #495057;
        font-weight: 500;
    }
    .form-control:focus {
        border-color: #da3544;
        box-shadow: 0 0 0 0.2rem rgba(218, 53, 68, 0.25);
        color: #212529;
    }
    .btn-primary {
        background-color: #da3544;
        border-color: #da3544;
        font-weight: 600;
    }
    .btn-primary:hover {
        background-color: #c12e3f;
        border-color: #b02a37;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .card-title {
        color: #212529;
        font-weight: 600;
    }
    .text-dark {
        color: #212529 !important;
    }
    .font-weight-medium {
        font-weight: 500 !important;
    }
    .border-left-primary {
        border-left: 4px solid #da3544 !important;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .pagination .page-link {
        color: #da3544;
        border-color: #dee2e6;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
    }
    .pagination .page-link:hover {
        color: #b02a37;
        background-color: #f8f9fa;
        border-color: #da3544;
    }
    .pagination .page-item.active .page-link {
        background-color: #da3544;
        border-color: #da3544;
        color: white;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="userBiasaCount">{{ $statistics['user_biasa'] }}</h3>
                <p>User Biasa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="pengusahaRentalCount">{{ $statistics['pengusaha_rental'] }}</h3>
                <p>Pengusaha Rental</p>
            </div>
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="emailVerifiedCount">{{ $statistics['email_verified'] }}</h3>
                <p>Email Terverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="emailUnverifiedCount">{{ $statistics['email_unverified'] }}</h3>
                <p>Belum Verifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Pencarian</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="search" class="font-weight-bold text-dark">Cari (Nama/Email/NIK/HP)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="role" class="font-weight-bold text-dark">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="">Semua Role</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User Biasa</option>
                                <option value="pengusaha_rental" {{ request('role') == 'pengusaha_rental' ? 'selected' : '' }}>Pengusaha Rental</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="email_status" class="font-weight-bold text-dark">Status Email</label>
                            <select class="form-control" id="email_status" name="email_status">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('email_status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="unverified" {{ request('email_status') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" id="searchBtn" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="resetFilterRow" style="display: none;">
                        <div class="col-12">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    <span id="dataCount">Daftar User ({{ $users->total() }} data)</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pengguna.buat') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Tambah User</span>
                    </a>
                </div>
            </div>
            <div class="card-body p-0 position-relative">
                <!-- Loading Overlay -->
                <div id="loadingOverlay" class="loading-overlay d-none">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-dark font-weight-bold">Memuat data...</p>
                    </div>
                </div>

                <!-- Table Content -->
                <div id="tableContent">
                    @include('admin.users.partials.table', ['users' => $users])
                </div>
            </div>
            <!-- Pagination -->
            <div class="card-footer" id="paginationContainer">
                @if($users->hasPages())
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info text-dark font-weight-medium">
                            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }}
                            dari {{ $users->total() }} data
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <nav>
                            <ul class="pagination justify-content-end">
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i> Sebelumnya</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="#" data-page="{{ $users->currentPage() - 1 }}">
                                            <i class="fas fa-chevron-left"></i> Sebelumnya
                                        </a>
                                    </li>
                                @endif

                                @php
                                    $start = max(1, $users->currentPage() - 2);
                                    $end = min($users->lastPage(), $users->currentPage() + 2);
                                @endphp

                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="#" data-page="1">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $users->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if($end < $users->lastPage())
                                    @if($end < $users->lastPage() - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="#" data-page="{{ $users->lastPage() }}">{{ $users->lastPage() }}</a>
                                    </li>
                                @endif

                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="#" data-page="{{ $users->currentPage() + 1 }}">
                                            Selanjutnya <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Selanjutnya <i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;

    // Load data function
    function loadData(page = 1) {
        $('#loadingOverlay').removeClass('d-none');

        const formData = {
            search: $('#search').val(),
            role: $('#role').val(),
            email_status: $('#email_status').val(),
            page: page
        };

        $.ajax({
            url: '{{ route('admin.pengguna.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#tableContent').html(response.html);
                    $('#paginationContainer').html(response.pagination_html);
                    updateDataCount(response.pagination.total);
                    updateResetButton();
                    currentPage = page;
                }
            },
            error: function(xhr) {
                console.error('Error loading data:', xhr);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            },
            complete: function() {
                $('#loadingOverlay').addClass('d-none');
            }
        });
    }



    // Update data count
    function updateDataCount(total) {
        $('#dataCount').text('Daftar User (' + total + ' data)');
    }

    // Update reset button visibility
    function updateResetButton() {
        const hasFilters = $('#search').val() || $('#role').val() || $('#email_status').val();
        if (hasFilters) {
            $('#resetFilterRow').show();
        } else {
            $('#resetFilterRow').hide();
        }
    }

    // Search button click
    $('#searchBtn').on('click', function() {
        loadData(1);
    });

    // Auto-submit on select change
    $('#role, #email_status').on('change', function() {
        loadData(1);
    });

    // Enter key on search input
    $('#search').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            loadData(1);
        }
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        $('#search').val('');
        $('#role').val('');
        $('#email_status').val('');
        loadData(1);
    });

    // Pagination click handler
    $(document).on('click', '#paginationContainer .page-link[data-page]', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            loadData(page);
        }
    });

    // Initialize reset button state
    updateResetButton();
});
</script>
@endpush
