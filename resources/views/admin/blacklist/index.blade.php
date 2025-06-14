@extends('layouts.admin')

@section('title', 'Daftar Blacklist')
@section('page-title', 'Daftar Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Daftar Blacklist</li>
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
                            <label for="search" class="font-weight-bold text-dark">Cari (Nama/NIK/HP)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="jenis_rental" class="font-weight-bold text-dark">Jenis Rental</label>
                            <select class="form-control" id="jenis_rental" name="jenis_rental">
                                <option value="">Semua</option>
                                <option value="Rental Mobil" {{ request('jenis_rental') == 'Rental Mobil' ? 'selected' : '' }}>Rental Mobil</option>
                                <option value="Rental Motor" {{ request('jenis_rental') == 'Rental Motor' ? 'selected' : '' }}>Rental Motor</option>
                                <option value="Kamera" {{ request('jenis_rental') == 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                <option value="Lainnya" {{ request('jenis_rental') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="status_validitas" class="font-weight-bold text-dark">Status</label>
                            <select class="form-control" id="status_validitas" name="status_validitas">
                                <option value="">Semua</option>
                                <option value="Pending" {{ request('status_validitas') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Valid" {{ request('status_validitas') == 'Valid' ? 'selected' : '' }}>Valid</option>
                                <option value="Invalid" {{ request('status_validitas') == 'Invalid' ? 'selected' : '' }}>Invalid</option>
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
                    <i class="fas fa-list mr-2"></i>
                    <span id="dataCount">Daftar Blacklist ({{ $blacklists->total() }} data)</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.daftar-hitam.buat') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Tambah Blacklist</span>
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
                    @include('admin.blacklist.partials.table', ['blacklists' => $blacklists, 'reportCounts' => $reportCounts])
                </div>
            </div>

            <!-- Pagination -->
            <div class="card-footer" id="paginationContainer">
                @if($blacklists->hasPages())
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info text-dark font-weight-medium" id="paginationInfo">
                            Menampilkan {{ $blacklists->firstItem() }} sampai {{ $blacklists->lastItem() }}
                            dari {{ $blacklists->total() }} data
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7" id="paginationLinks">
                        {{ $blacklists->links() }}
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
            jenis_rental: $('#jenis_rental').val(),
            status_validitas: $('#status_validitas').val(),
            page: page
        };

        $.ajax({
            url: '{{ route('admin.daftar-hitam.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#tableContent').html(response.html);
                    updatePagination(response.pagination);
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

    // Update pagination
    function updatePagination(pagination) {
        if (pagination.last_page > 1) {
            let paginationHtml = '<div class="row"><div class="col-sm-12 col-md-5">';
            paginationHtml += '<div class="dataTables_info text-dark font-weight-medium">';
            paginationHtml += 'Menampilkan ' + pagination.from + ' sampai ' + pagination.to + ' dari ' + pagination.total + ' data';
            paginationHtml += '</div></div><div class="col-sm-12 col-md-7">';
            paginationHtml += '<nav><ul class="pagination justify-content-end">';

            // Previous button
            if (pagination.current_page > 1) {
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (pagination.current_page - 1) + '"><i class="fas fa-chevron-left"></i> Sebelumnya</a></li>';
            } else {
                paginationHtml += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i> Sebelumnya</span></li>';
            }

            // Page numbers
            let startPage = Math.max(1, pagination.current_page - 2);
            let endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            if (startPage > 1) {
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
                if (startPage > 2) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i === pagination.current_page) {
                    paginationHtml += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
                } else {
                    paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                }
            }

            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + pagination.last_page + '">' + pagination.last_page + '</a></li>';
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (pagination.current_page + 1) + '">Selanjutnya <i class="fas fa-chevron-right"></i></a></li>';
            } else {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">Selanjutnya <i class="fas fa-chevron-right"></i></span></li>';
            }

            paginationHtml += '</ul></nav></div></div>';
            $('#paginationContainer').html(paginationHtml).show();
        } else {
            $('#paginationContainer').hide();
        }
    }

    // Update data count
    function updateDataCount(total) {
        $('#dataCount').text('Daftar Blacklist (' + total + ' data)');
    }

    // Update reset button visibility
    function updateResetButton() {
        const hasFilters = $('#search').val() || $('#jenis_rental').val() || $('#status_validitas').val();
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
    $('#jenis_rental, #status_validitas').on('change', function() {
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
        $('#jenis_rental').val('');
        $('#status_validitas').val('');
        loadData(1);
    });

    // Pagination click handler
    $(document).on('click', '.page-link', function(e) {
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
