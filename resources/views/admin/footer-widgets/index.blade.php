@extends('admin.layouts.app')

@section('title', 'Kelola Footer Widget')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-puzzle-piece me-2"></i>
            Kelola Footer Widget
        </h1>
        <a href="{{ route('admin.footer-widgets.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>
            Tambah Widget
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Widget List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Daftar Widget Footer
            </h6>
            <small class="text-muted">Total: {{ $widgets->count() }} widget</small>
        </div>
        <div class="card-body">
            @if($widgets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="widgetTable">
                        <thead>
                            <tr>
                                <th width="50">
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th width="100">Urutan</th>
                                <th width="100">Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-widgets">
                            @foreach($widgets as $widget)
                            <tr data-id="{{ $widget->id }}">
                                <td class="text-center">
                                    <i class="fas fa-grip-vertical text-muted handle" style="cursor: move;"></i>
                                </td>
                                <td>
                                    <strong>{{ $widget->title }}</strong>
                                    @if($widget->css_class)
                                        <br><small class="text-muted">CSS: {{ $widget->css_class }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ \App\Models\FooterWidget::getTypes()[$widget->type] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary">{{ $widget->order }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               data-id="{{ $widget->id }}"
                                               {{ $widget->is_active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.footer-widgets.show', $widget) }}" 
                                           class="btn btn-info btn-sm" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.footer-widgets.edit', $widget) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm delete-widget" 
                                                data-id="{{ $widget->id }}"
                                                data-title="{{ $widget->title }}"
                                                title="Hapus">
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
                    <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada widget footer</h5>
                    <p class="text-muted">Klik tombol "Tambah Widget" untuk membuat widget footer pertama.</p>
                    <a href="{{ route('admin.footer-widgets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Widget Pertama
                    </a>
                </div>
            @endif
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
                <p>Apakah Anda yakin ingin menghapus widget <strong id="widget-title"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
<style>
.handle {
    cursor: move !important;
}
.ui-sortable-helper {
    background: #f8f9fc;
    border: 2px dashed #5a5c69;
}
.form-check-input:checked {
    background-color: #1cc88a;
    border-color: #1cc88a;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Make table sortable
    $("#sortable-widgets").sortable({
        handle: '.handle',
        update: function(event, ui) {
            let widgets = [];
            $('#sortable-widgets tr').each(function(index) {
                widgets.push({
                    id: $(this).data('id'),
                    order: index + 1
                });
            });
            
            updateWidgetOrder(widgets);
        }
    });

    // Status toggle
    $('.status-toggle').change(function() {
        const widgetId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/footer-widgets/${widgetId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                }
            },
            error: function() {
                // Revert checkbox state
                $(this).prop('checked', !isChecked);
                showAlert('error', 'Terjadi kesalahan saat mengubah status');
            }
        });
    });

    // Delete widget
    $('.delete-widget').click(function() {
        const widgetId = $(this).data('id');
        const widgetTitle = $(this).data('title');
        
        $('#widget-title').text(widgetTitle);
        $('#deleteModal').modal('show');
        
        $('#confirm-delete').off('click').on('click', function() {
            deleteWidget(widgetId);
        });
    });

    function updateWidgetOrder(widgets) {
        $.ajax({
            url: '{{ route("admin.footer-widgets.update-order") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                widgets: widgets
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    // Update order badges
                    widgets.forEach(function(widget, index) {
                        $(`tr[data-id="${widget.id}"] .badge-secondary`).text(widget.order);
                    });
                }
            },
            error: function() {
                showAlert('error', 'Terjadi kesalahan saat mengubah urutan');
                location.reload();
            }
        });
    }

    function deleteWidget(widgetId) {
        $.ajax({
            url: `/admin/footer-widgets/${widgetId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    showAlert('success', response.message);
                    $(`tr[data-id="${widgetId}"]`).fadeOut(function() {
                        $(this).remove();
                        if ($('#sortable-widgets tr').length === 0) {
                            location.reload();
                        }
                    });
                }
            },
            error: function() {
                showAlert('error', 'Terjadi kesalahan saat menghapus widget');
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alert);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }
});
</script>
@endpush
