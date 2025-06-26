@extends('admin.layouts.app')

@section('title', 'Detail Footer Widget')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye me-2"></i>
            Detail Footer Widget
        </h1>
        <div>
            <a href="{{ route('admin.footer-widgets.edit', $footerWidget) }}" class="btn btn-warning btn-sm me-2">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>
            <a href="{{ route('admin.footer-widgets.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Widget Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Widget
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td>{{ $footerWidget->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Judul:</strong></td>
                                    <td>{{ $footerWidget->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe:</strong></td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ \App\Models\FooterWidget::getTypes()[$footerWidget->type] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Urutan:</strong></td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $footerWidget->order }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $footerWidget->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $footerWidget->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>CSS Class:</strong></td>
                                    <td>
                                        @if($footerWidget->css_class)
                                            <code>{{ $footerWidget->css_class }}</code>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $footerWidget->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $footerWidget->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget Content -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-code me-2"></i>
                        Konten Widget
                    </h6>
                </div>
                <div class="card-body">
                    @if($footerWidget->type === 'text')
                        <h6>Konten Teks:</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $footerWidget->content ?: 'Tidak ada konten' }}
                        </div>
                    
                    @elseif($footerWidget->type === 'links')
                        <h6>Daftar Link:</h6>
                        @if($footerWidget->data && isset($footerWidget->data['links']))
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Teks</th>
                                            <th>URL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($footerWidget->data['links'] as $index => $link)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $link['text'] }}</td>
                                            <td>
                                                <a href="{{ $link['url'] }}" target="_blank" class="text-primary">
                                                    {{ $link['url'] }}
                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada link yang dikonfigurasi</p>
                        @endif
                    
                    @elseif($footerWidget->type === 'contact')
                        <h6>Informasi Kontak:</h6>
                        @if($footerWidget->data)
                            <div class="row">
                                @if(isset($footerWidget->data['address']))
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Alamat:</strong>
                                    <p class="mb-0">{{ $footerWidget->data['address'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($footerWidget->data['phone']))
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-phone me-2"></i>Telepon:</strong>
                                    <p class="mb-0">{{ $footerWidget->data['phone'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($footerWidget->data['email']))
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-envelope me-2"></i>Email:</strong>
                                    <p class="mb-0">{{ $footerWidget->data['email'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($footerWidget->data['whatsapp']))
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fab fa-whatsapp me-2"></i>WhatsApp:</strong>
                                    <p class="mb-0">{{ $footerWidget->data['whatsapp'] }}</p>
                                </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted">Tidak ada informasi kontak yang dikonfigurasi</p>
                        @endif
                    
                    @elseif($footerWidget->type === 'social')
                        <h6>Media Sosial:</h6>
                        @if($footerWidget->data && isset($footerWidget->data['social']))
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Platform</th>
                                            <th>URL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($footerWidget->data['social'] as $index => $social)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <i class="{{ $footerWidget->getSocialIcon($social['platform']) }} me-2"></i>
                                                {{ ucfirst($social['platform']) }}
                                            </td>
                                            <td>
                                                <a href="{{ $social['url'] }}" target="_blank" class="text-primary">
                                                    {{ $social['url'] }}
                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada media sosial yang dikonfigurasi</p>
                        @endif
                    
                    @elseif($footerWidget->type === 'custom')
                        <h6>HTML Kustom:</h6>
                        <div class="bg-light p-3 rounded">
                            <pre><code>{{ $footerWidget->content ?: 'Tidak ada konten HTML' }}</code></pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>
                        Preview Widget
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-dark text-light p-3 rounded">
                        <h6 class="text-light">{{ $footerWidget->title }}</h6>
                        <div>
                            {!! $footerWidget->formatted_content !!}
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Preview menampilkan bagaimana widget akan terlihat di footer website.
                    </small>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.footer-widgets.edit', $footerWidget) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Widget
                        </a>
                        
                        <button type="button" class="btn btn-{{ $footerWidget->is_active ? 'secondary' : 'success' }}" 
                                onclick="toggleStatus({{ $footerWidget->id }})">
                            <i class="fas fa-{{ $footerWidget->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $footerWidget->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="deleteWidget({{ $footerWidget->id }})">
                            <i class="fas fa-trash me-2"></i>
                            Hapus Widget
                        </button>
                    </div>
                </div>
            </div>

            <!-- Widget Data (JSON) -->
            @if($footerWidget->data)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-database me-2"></i>
                        Data JSON
                    </h6>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-2 rounded small"><code>{{ json_encode($footerWidget->data, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(widgetId) {
    if (confirm('Apakah Anda yakin ingin mengubah status widget ini?')) {
        $.ajax({
            url: `/admin/footer-widgets/${widgetId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat mengubah status');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengubah status');
            }
        });
    }
}

function deleteWidget(widgetId) {
    if (confirm('Apakah Anda yakin ingin menghapus widget ini? Tindakan ini tidak dapat dibatalkan.')) {
        $.ajax({
            url: `/admin/footer-widgets/${widgetId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '{{ route("admin.footer-widgets.index") }}';
                } else {
                    alert('Terjadi kesalahan saat menghapus widget');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus widget');
            }
        });
    }
}
</script>
@endpush
