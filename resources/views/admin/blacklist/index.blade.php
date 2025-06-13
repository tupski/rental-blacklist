@extends('layouts.admin')

@section('title', 'Daftar Blacklist')
@section('page-title', 'Daftar Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Daftar Blacklist</li>
@endsection

@section('content')
<!-- Filter Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Pencarian</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.blacklist.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="search">Cari (Nama/NIK/HP)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="jenis_rental">Jenis Rental</label>
                            <select class="form-control" id="jenis_rental" name="jenis_rental">
                                <option value="">Semua</option>
                                <option value="Rental Mobil" {{ request('jenis_rental') == 'Rental Mobil' ? 'selected' : '' }}>Rental Mobil</option>
                                <option value="Rental Motor" {{ request('jenis_rental') == 'Rental Motor' ? 'selected' : '' }}>Rental Motor</option>
                                <option value="Kamera" {{ request('jenis_rental') == 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                <option value="Lainnya" {{ request('jenis_rental') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="status_validitas">Status</label>
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
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'jenis_rental', 'status_validitas']))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('admin.blacklist.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                    @endif
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
                <h3 class="card-title">Daftar Blacklist ({{ $blacklists->total() }} data)</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.blacklist.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Tambah Blacklist</span>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Mobile-friendly table -->
                <div class="d-none d-lg-block">
                    <!-- Desktop Table -->
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Nama Lengkap</th>
                                <th width="15%">NIK</th>
                                <th width="12%">No. HP</th>
                                <th width="12%">Jenis Rental</th>
                                <th width="8%">Laporan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Tanggal</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blacklists as $blacklist)
                            <tr>
                                <td>{{ $blacklist->id }}</td>
                                <td>
                                    <strong>{{ $blacklist->nama_lengkap }}</strong>
                                    <br><small class="text-muted">{{ $blacklist->user->name ?? 'N/A' }}</small>
                                </td>
                                <td><code>{{ $blacklist->nik }}</code></td>
                                <td>{{ $blacklist->no_hp }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $blacklist->jenis_rental }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $reportCounts[$blacklist->nik] ?? 0 }} laporan
                                    </span>
                                </td>
                                <td>
                                    @if($blacklist->status_validitas === 'Valid')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($blacklist->status_validitas === 'Invalid')
                                        <span class="badge badge-danger">Invalid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $blacklist->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.blacklist.show', $blacklist->id) }}">
                                                <i class="fas fa-eye text-info"></i> Lihat
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.blacklist.edit', $blacklist->id) }}">
                                                <i class="fas fa-edit text-warning"></i> Edit
                                            </a>
                                            @if($blacklist->status_validitas === 'Pending')
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.blacklist.validate', $blacklist->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Validasi data ini?')">
                                                        <i class="fas fa-check text-success"></i> Valid
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.blacklist.invalidate', $blacklist->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Tolak data ini?')">
                                                        <i class="fas fa-times text-warning"></i> Pending
                                                    </button>
                                                </form>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.blacklist.destroy', $blacklist->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Hapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Tidak ada data blacklist</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-lg-none">
                    @forelse($blacklists as $blacklist)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h6 class="card-title mb-1">{{ $blacklist->nama_lengkap }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-id-card"></i> {{ $blacklist->nik }}<br>
                                            <i class="fas fa-phone"></i> {{ $blacklist->no_hp }}<br>
                                            <i class="fas fa-car"></i> {{ $blacklist->jenis_rental }}<br>
                                            <i class="fas fa-flag"></i> {{ $reportCounts[$blacklist->nik] ?? 0 }} laporan
                                        </small>
                                    </p>
                                </div>
                                <div class="col-4 text-right">
                                    @if($blacklist->status_validitas === 'Valid')
                                        <span class="badge badge-success mb-2">Valid</span>
                                    @elseif($blacklist->status_validitas === 'Invalid')
                                        <span class="badge badge-danger mb-2">Invalid</span>
                                    @else
                                        <span class="badge badge-warning mb-2">Pending</span>
                                    @endif
                                    <br>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.blacklist.show', $blacklist->id) }}">
                                                <i class="fas fa-eye text-info"></i> Lihat
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.blacklist.edit', $blacklist->id) }}">
                                                <i class="fas fa-edit text-warning"></i> Edit
                                            </a>
                                            @if($blacklist->status_validitas === 'Pending')
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.blacklist.validate', $blacklist->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Validasi data ini?')">
                                                        <i class="fas fa-check text-success"></i> Valid
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.blacklist.invalidate', $blacklist->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Tolak data ini?')">
                                                        <i class="fas fa-times text-warning"></i> Pending
                                                    </button>
                                                </form>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.blacklist.destroy', $blacklist->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Hapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> {{ $blacklist->user->name ?? 'N/A' }} •
                                        <i class="fas fa-calendar"></i> {{ $blacklist->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data blacklist</h5>
                        <p class="text-muted">Coba ubah filter pencarian Anda</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @if($blacklists->hasPages())
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info">
                            Menampilkan {{ $blacklists->firstItem() }} sampai {{ $blacklists->lastItem() }}
                            dari {{ $blacklists->total() }} data
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        {{ $blacklists->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on select change
    $('#jenis_rental, #status_validitas').on('change', function() {
        $('#filterForm').submit();
    });

    // Add loading state to search button
    $('#filterForm').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Mencari...');
        submitBtn.prop('disabled', true);
    });
});
</script>
@endpush
