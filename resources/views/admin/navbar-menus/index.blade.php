@extends('layouts.admin')

@section('title', 'Kelola Menu Navbar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-bars me-2"></i>
                        Kelola Menu Navbar
                    </h3>
                    <a href="{{ route('admin.menu-navbar.buat') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Menu
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

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Drag & drop untuk mengubah urutan menu. Menu akan ditampilkan sesuai urutan yang Anda atur.
                    </div>

                    <div id="menu-list" class="sortable-list">
                        @forelse($menus as $menu)
                            <div class="menu-item card mb-3" data-id="{{ $menu->id }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <i class="fas fa-grip-vertical text-muted drag-handle" style="cursor: move;"></i>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                @if($menu->icon)
                                                    <i class="{{ $menu->icon }} me-2 text-primary"></i>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $menu->title }}</h6>
                                                    <small class="text-muted">
                                                        @if($menu->route_name)
                                                            Route: {{ $menu->route_name }}
                                                        @else
                                                            URL: {{ $menu->url }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge bg-{{ $menu->visibility === 'all' ? 'primary' : ($menu->visibility === 'guest' ? 'secondary' : ($menu->visibility === 'auth' ? 'success' : ($menu->visibility === 'admin' ? 'danger' : 'warning'))) }}">
                                                {{ ucfirst($menu->visibility) }}
                                            </span>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" 
                                                       type="checkbox" 
                                                       data-id="{{ $menu->id }}"
                                                       {{ $menu->is_active ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.menu-navbar.edit', $menu) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.menu-navbar.hapus', $menu) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if($menu->children->count() > 0)
                                        <div class="mt-3 ps-4">
                                            <h6 class="text-muted mb-2">
                                                <i class="fas fa-level-down-alt me-1"></i>
                                                Sub Menu:
                                            </h6>
                                            @foreach($menu->children as $child)
                                                <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        @if($child->icon)
                                                            <i class="{{ $child->icon }} me-2 text-secondary"></i>
                                                        @endif
                                                        <span>{{ $child->title }}</span>
                                                        <span class="badge bg-light text-dark ms-2">{{ ucfirst($child->visibility) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="form-check form-switch d-inline me-2">
                                                            <input class="form-check-input status-toggle" 
                                                                   type="checkbox" 
                                                                   data-id="{{ $child->id }}"
                                                                   {{ $child->is_active ? 'checked' : '' }}>
                                                        </div>
                                                        <a href="{{ route('admin.menu-navbar.edit', $child) }}" 
                                                           class="btn btn-sm btn-outline-warning me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.menu-navbar.hapus', $child) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Yakin ingin menghapus sub menu ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-bars fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada menu navbar yang dibuat.</p>
                                <a href="{{ route('admin.menu-navbar.buat') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Buat Menu Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
<style>
.menu-item {
    transition: all 0.3s ease;
}

.menu-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    transform: scale(1.02);
}

.drag-handle:hover {
    color: #007bff !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    const menuList = document.getElementById('menu-list');
    if (menuList) {
        Sortable.create(menuList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                updateMenuOrder();
            }
        });
    }

    // Status toggle
    $('.status-toggle').change(function() {
        const menuId = $(this).data('id');
        const isActive = $(this).is(':checked');

        $.ajax({
            url: `/admin/menu-navbar/${menuId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat mengubah status menu.');
                // Revert checkbox
                $(this).prop('checked', !isActive);
            }
        });
    });

    function updateMenuOrder() {
        const menus = [];
        $('#menu-list .menu-item').each(function(index) {
            menus.push({
                id: $(this).data('id'),
                order: index
            });
        });

        $.ajax({
            url: '{{ route("admin.menu-navbar.update-order") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                menus: menus
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat mengubah urutan menu.');
                location.reload();
            }
        });
    }
});
</script>
@endpush
