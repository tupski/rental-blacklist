@extends('layouts.admin')

@section('title', 'Kelola Atribut - ' . $types[$type])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-tags me-2"></i>
                        Kelola Atribut: {{ $types[$type] }}
                    </h3>
                    <a href="{{ route('admin.atribut.buat', ['type' => $type]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah {{ $types[$type] }}
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

                    <!-- Type Tabs -->
                    <div class="mb-4">
                        <ul class="nav nav-pills">
                            @foreach($types as $typeKey => $typeLabel)
                                <li class="nav-item">
                                    <a class="nav-link {{ $type === $typeKey ? 'active' : '' }}" 
                                       href="{{ route('admin.atribut.indeks', ['type' => $typeKey]) }}">
                                        {{ $typeLabel }}
                                        <span class="badge bg-light text-dark ms-1">
                                            {{ \App\Models\Attribute::ofType($typeKey)->count() }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Drag & drop untuk mengubah urutan atribut. Atribut akan ditampilkan sesuai urutan yang Anda atur di form laporan.
                    </div>

                    <div id="attribute-list" class="sortable-list">
                        @forelse($attributes as $attribute)
                            <div class="attribute-item card mb-3" data-id="{{ $attribute->id }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <i class="fas fa-grip-vertical text-muted drag-handle" style="cursor: move;"></i>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $attribute->name }}</h6>
                                                    <small class="text-muted">
                                                        Value: <code>{{ $attribute->value }}</code>
                                                    </small>
                                                    @if($attribute->description)
                                                        <br><small class="text-muted">{{ $attribute->description }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="badge bg-secondary">
                                                Urutan: {{ $attribute->order }}
                                            </span>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            @if($attribute->is_default)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-star me-1"></i>
                                                    Default
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" 
                                                       type="checkbox" 
                                                       data-id="{{ $attribute->id }}"
                                                       {{ $attribute->is_active ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.atribut.edit', $attribute) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.atribut.hapus', $attribute) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus atribut ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada {{ strtolower($types[$type]) }} yang dibuat.</p>
                                <a href="{{ route('admin.atribut.buat', ['type' => $type]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Buat {{ $types[$type] }} Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($attributes->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $attributes->appends(['type' => $type])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
<style>
.attribute-item {
    transition: all 0.3s ease;
}

.attribute-item:hover {
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

.nav-pills .nav-link {
    color: #6c757d;
}

.nav-pills .nav-link.active {
    background-color: #da3544;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    const attributeList = document.getElementById('attribute-list');
    if (attributeList) {
        Sortable.create(attributeList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                updateAttributeOrder();
            }
        });
    }

    // Status toggle
    $('.status-toggle').change(function() {
        const attributeId = $(this).data('id');
        const isActive = $(this).is(':checked');

        $.ajax({
            url: `/admin/atribut/${attributeId}/toggle-status`,
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
                toastr.error('Terjadi kesalahan saat mengubah status atribut.');
                // Revert checkbox
                $(this).prop('checked', !isActive);
            }
        });
    });

    function updateAttributeOrder() {
        const attributes = [];
        $('#attribute-list .attribute-item').each(function(index) {
            attributes.push({
                id: $(this).data('id'),
                order: index
            });
        });

        $.ajax({
            url: '{{ route("admin.atribut.update-order") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                attributes: attributes
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update order badges
                    $('#attribute-list .attribute-item').each(function(index) {
                        $(this).find('.badge.bg-secondary').text('Urutan: ' + index);
                    });
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat mengubah urutan atribut.');
                location.reload();
            }
        });
    }
});
</script>
@endpush
