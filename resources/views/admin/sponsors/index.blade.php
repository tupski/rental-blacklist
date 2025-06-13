@extends('layouts.main')

@section('title', 'Kelola Sponsor')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-6 fw-bold text-dark mb-2">
                                    <i class="fas fa-handshake text-primary me-3"></i>
                                    Kelola Sponsor
                                </h1>
                                <p class="text-muted mb-1">
                                    Manajemen sponsor dan partnership
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ now()->format('l, d F Y') }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Tambah Sponsor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Sponsors Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    Daftar Sponsor ({{ $sponsors->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($sponsors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Logo</th>
                                    <th>Nama</th>
                                    <th>Website</th>
                                    <th>Posisi</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sponsors as $sponsor)
                                <tr>
                                    <td>
                                        <img src="{{ $sponsor->logo_url }}" 
                                             alt="{{ $sponsor->name }}" 
                                             class="rounded"
                                             style="width: 60px; height: 40px; object-fit: contain;">
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $sponsor->name }}</div>
                                        @if($sponsor->description)
                                            <small class="text-muted">{{ Str::limit($sponsor->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ Str::limit($sponsor->website_url, 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($sponsor->position === 'home_top') bg-primary
                                            @elseif($sponsor->position === 'home_bottom') bg-success
                                            @else bg-secondary @endif">
                                            @if($sponsor->position === 'home_top') Home Top
                                            @elseif($sponsor->position === 'home_bottom') Home Bottom
                                            @else Footer @endif
                                        </span>
                                    </td>
                                    <td>{{ $sponsor->sort_order }}</td>
                                    <td>
                                        @if($sponsor->isCurrentlyActive())
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if($sponsor->start_date)
                                                {{ $sponsor->start_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                            s/d
                                            @if($sponsor->end_date)
                                                {{ $sponsor->end_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.sponsors.show', $sponsor) }}" 
                                               class="btn btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sponsors.edit', $sponsor) }}" 
                                               class="btn btn-outline-success" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteSponsor({{ $sponsor->id }})" 
                                                    class="btn btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-handshake text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">Belum Ada Sponsor</h5>
                        <p class="text-muted">Mulai tambahkan sponsor untuk mendukung platform ini</p>
                        <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Tambah Sponsor Pertama
                        </a>
                    </div>
                @endif
            </div>
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
                <p>Apakah Anda yakin ingin menghapus sponsor ini?</p>
                <p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSponsor(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/sponsors/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
