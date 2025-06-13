@extends('layouts.admin')

@section('title', 'Daftar Blacklist')
@section('page-title', 'Daftar Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Daftar Blacklist</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Blacklist</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.blacklist.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Blacklist
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="blacklistTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Lengkap</th>
                                <th>NIK</th>
                                <th>No. HP</th>
                                <th>Jenis Rental</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blacklists as $blacklist)
                            <tr>
                                <td>{{ $blacklist->id }}</td>
                                <td>{{ $blacklist->nama_lengkap }}</td>
                                <td>{{ $blacklist->nik }}</td>
                                <td>{{ $blacklist->no_hp }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $blacklist->jenis_rental }}</span>
                                </td>
                                <td>{{ $blacklist->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if($blacklist->status_validitas === 'Valid')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($blacklist->status_validitas === 'Invalid')
                                        <span class="badge badge-danger">Invalid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $blacklist->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.blacklist.show', $blacklist->id) }}" 
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blacklist.edit', $blacklist->id) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($blacklist->status_validitas === 'Pending')
                                            <form action="{{ route('admin.blacklist.validate', $blacklist->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Validasi" onclick="return confirm('Validasi data ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.blacklist.invalidate', $blacklist->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Tolak" onclick="return confirm('Tolak data ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.blacklist.destroy', $blacklist->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    title="Hapus" onclick="return confirm('Hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data blacklist</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($blacklists->hasPages())
            <div class="card-footer">
                {{ $blacklists->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#blacklistTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "paging": false, // Disable DataTables pagination since we use Laravel pagination
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)"
        }
    });
});
</script>
@endpush
