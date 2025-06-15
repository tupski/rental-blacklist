@extends('layouts.admin')

@section('title', 'Laporan')

@section('content_header')
    <h1>Laporan Sistem</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Generate Laporan
                </h3>
            </div>
            <div class="card-body">
                <form id="reportForm" action="{{ route('admin.laporan.data') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report_type">Jenis Laporan</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="all">Semua Data</option>
                                    <option value="blacklist">Data Blacklist</option>
                                    <option value="users">Data Pengguna</option>
                                    <option value="topup">Data Topup</option>
                                    <option value="guest_reports">Laporan Tamu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Tanggal Mulai</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{ date('Y-m-01') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Tanggal Selesai</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-block">
                                    <button type="button" id="generateReport" class="btn btn-primary">
                                        <i class="fas fa-chart-bar"></i> Generate
                                    </button>
                                    <button type="button" id="exportReport" class="btn btn-success">
                                        <i class="fas fa-download"></i> Export CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row" id="reportResults" style="display: none;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-table mr-2"></i>
                    Hasil Laporan
                </h3>
                <div class="card-tools">
                    <span id="reportPeriod" class="badge badge-info"></span>
                </div>
            </div>
            <div class="card-body">
                <div id="reportContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_blacklist'] ?? 0 }}</h3>
                <p>Total Blacklist</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
            <a href="{{ route('admin.daftar-hitam.indeks') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Total Pengguna</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.pengguna.indeks') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_reports'] ?? 0 }}</h3>
                <p>Laporan Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('admin.laporan-tamu.indeks') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['pending_topups'] ?? 0 }}</h3>
                <p>Topup Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <a href="{{ route('admin.topup.indeks') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#generateReport').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);
        
        const formData = $('#reportForm').serialize();
        
        $.get('{{ route("admin.laporan.data") }}?' + formData)
            .done(function(response) {
                if (response.success) {
                    $('#reportPeriod').text('Periode: ' + response.period.from + ' - ' + response.period.to);
                    $('#reportContent').html(formatReportData(response.data, $('#report_type').val()));
                    $('#reportResults').show();
                } else {
                    toastr.error('Gagal mengambil data laporan');
                }
            })
            .fail(function() {
                toastr.error('Terjadi kesalahan saat mengambil data');
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });
    
    $('#exportReport').on('click', function() {
        const formData = $('#reportForm').serialize();
        window.open('{{ route("admin.laporan.ekspor") }}?' + formData, '_blank');
    });
    
    function formatReportData(data, type) {
        if (type === 'all') {
            return `
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-ban"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Blacklist</span>
                                <span class="info-box-number">${data.blacklists || 0}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pengguna Baru</span>
                                <span class="info-box-number">${data.users || 0}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Topup</span>
                                <span class="info-box-number">${data.topups || 0}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Format table for specific data types
            let html = '<div class="table-responsive"><table class="table table-bordered table-striped">';
            
            if (data.length > 0) {
                // Generate table headers based on first item
                const firstItem = data[0];
                html += '<thead><tr>';
                Object.keys(firstItem).forEach(key => {
                    html += `<th>${key.replace('_', ' ').toUpperCase()}</th>`;
                });
                html += '</tr></thead><tbody>';
                
                // Generate table rows
                data.forEach(item => {
                    html += '<tr>';
                    Object.values(item).forEach(value => {
                        html += `<td>${value || '-'}</td>`;
                    });
                    html += '</tr>';
                });
                html += '</tbody>';
            } else {
                html += '<tbody><tr><td colspan="100%" class="text-center">Tidak ada data</td></tr></tbody>';
            }
            
            html += '</table></div>';
            return html;
        }
    }
});
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
