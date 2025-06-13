@extends('layouts.admin')

@section('title', 'Manajemen Sponsor')
@section('page-title', 'Manajemen Sponsor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Sponsor</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Sponsor</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Sponsor
                    </a>
                </div>
            </div>
            <div class="card-body">

                @if($sponsors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="sponsorsTable">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Nama</th>
                                    <th>Website</th>
                                    <th>Status</th>
                                    <th>Urutan</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sponsors as $sponsor)
                                <tr>
                                    <td>
                                        @if($sponsor->logo)
                                            <img src="{{ asset('storage/sponsors/logos/' . $sponsor->logo) }}"
                                                 alt="{{ $sponsor->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 40px; object-fit: contain;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $sponsor->name }}</strong>
                                        @if($sponsor->description)
                                            <br><small class="text-muted">{{ Str::limit($sponsor->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sponsor->website_url)
                                            <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                {{ Str::limit($sponsor->website_url, 30) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sponsor->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $sponsor->sort_order ?? 0 }}</td>
                                    <td>{{ $sponsor->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.sponsors.show', $sponsor) }}"
                                               class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sponsors.edit', $sponsor) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.sponsors.toggle-status', $sponsor) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm"
                                                        title="{{ $sponsor->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $sponsor->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.sponsors.destroy', $sponsor) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        title="Hapus" onclick="return confirm('Hapus sponsor ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data sponsor</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Sponsor</h5>
                        <p class="text-muted">Mulai tambahkan sponsor untuk mendukung platform ini</p>
                        <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Sponsor Pertama
                        </a>
                    </div>
                @endif
            </div>
            @if($sponsors->hasPages())
            <div class="card-footer">
                {{ $sponsors->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#sponsorsTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "paging": false,
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
