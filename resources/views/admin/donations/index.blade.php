@extends('layouts.admin')

@section('title', 'Manajemen Donasi')
@section('page-title', 'Manajemen Donasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Donasi</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="totalCount">{{ $statistics['total'] }}</h3>
                <p>Total Donasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-heart"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="pendingCount">{{ $statistics['pending'] }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="confirmedCount">{{ $statistics['confirmed'] }}</h3>
                <p>Terkonfirmasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3 id="totalAmount">Rp {{ number_format($statistics['total_amount'], 0, ',', '.') }}</h3>
                <p>Total Terkumpul</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Pencarian</h3>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search">Cari (Nama/Email/Perusahaan)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                        </div>
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="donor_type">Tipe Donatur</label>
                            <select class="form-control" id="donor_type" name="donor_type">
                                <option value="">Semua Tipe</option>
                                <option value="individual" {{ request('donor_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="company" {{ request('donor_type') == 'company' ? 'selected' : '' }}>Perusahaan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" id="searchBtn" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heart mr-2"></i>
                    <span id="dataCount">Daftar Donasi ({{ $donations->total() }} data)</span>
                </h3>
            </div>
            <div class="card-body p-0">
                <div id="tableContent">
                    @include('admin.donations.partials.table', ['donations' => $donations])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load data function
    function loadData(page = 1) {
        const formData = {
            search: $('#search').val(),
            status: $('#status').val(),
            donor_type: $('#donor_type').val(),
            page: page
        };

        $.ajax({
            url: '{{ route('admin.donasi.indeks') }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#tableContent').html(response.html);
                    updateStatistics(response.statistics);
                }
            },
            error: function(xhr) {
                console.error('Error loading data:', xhr);
                alert('Terjadi kesalahan saat memuat data.');
            }
        });
    }

    function updateStatistics(stats) {
        $('#totalCount').text(stats.total);
        $('#pendingCount').text(stats.pending);
        $('#confirmedCount').text(stats.confirmed);
        $('#totalAmount').text('Rp ' + new Intl.NumberFormat('id-ID').format(stats.total_amount));
    }

    // Search button click
    $('#searchBtn').on('click', function() {
        loadData(1);
    });

    // Auto-submit on select change
    $('#status, #donor_type').on('change', function() {
        loadData(1);
    });

    // Enter key on search input
    $('#search').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            loadData(1);
        }
    });
});
</script>
@endpush
