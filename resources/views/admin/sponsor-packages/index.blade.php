@extends('layouts.admin')

@section('title', 'Kelola Paket Sponsor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-gift mr-2"></i>
                    Kelola Paket Sponsor
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.paket-sponsor.buat') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Paket
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Badge</th>
                                <th>Urutan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                            <tr>
                                <td>{{ $loop->iteration + ($packages->currentPage() - 1) * $packages->perPage() }}</td>
                                <td>
                                    <strong>{{ $package->name }}</strong>
                                    @if($package->description)
                                        <br><small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $package->formatted_price }}</td>
                                <td>{{ $package->formatted_duration }}</td>
                                <td>
                                    @if($package->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($package->is_popular)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> Populer
                                        </span>
                                    @else
                                        <span class="badge badge-light">-</span>
                                    @endif
                                </td>
                                <td>{{ $package->sort_order }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.paket-sponsor.tampil', $package) }}" 
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.paket-sponsor.edit', $package) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.paket-sponsor.hapus', $package) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    title="Hapus" 
                                                    onclick="return confirm('Hapus paket sponsor ini? Tindakan ini tidak dapat dibatalkan!')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada paket sponsor</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($packages->hasPages())
            <div class="card-footer">
                {{ $packages->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
