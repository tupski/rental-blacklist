@extends('layouts.admin')

@section('title', 'Kelola Halaman')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>
                        Kelola Halaman
                    </h3>
                    <a href="{{ route('admin.halaman.buat') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Halaman
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Judul</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Menu</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>
                                            <strong>{{ $page->title }}</strong>
                                            @if($page->excerpt)
                                                <br><small class="text-muted">{{ Str::limit($page->excerpt, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $page->slug }}</code>
                                            <br>
                                            <a href="{{ route('halaman.tampil', $page->slug) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                Lihat
                                            </a>
                                        </td>
                                        <td>
                                            @if($page->status === 'published')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Dipublikasi
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-eye-slash me-1"></i>
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($page->show_in_menu)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-bars me-1"></i>
                                                    Ya ({{ $page->menu_order }})
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>
                                                    Tidak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $page->created_at->format('d/m/Y H:i') }}
                                                <br>
                                                oleh {{ $page->creator->name }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.halaman.tampil', $page) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.halaman.edit', $page) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.halaman.hapus', $page) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus halaman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada halaman yang dibuat.</p>
                                            <a href="{{ route('admin.halaman.buat') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Buat Halaman Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pages->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $pages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
