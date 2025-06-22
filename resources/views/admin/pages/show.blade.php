@extends('layouts.admin')

@section('title', 'Lihat Halaman: ' . $page->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Lihat Halaman: {{ $page->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.halaman.indeks') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                        <a href="{{ route('admin.halaman.edit', $page) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit
                        </a>
                        <a href="{{ route('halaman.tampil', $page->slug) }}" 
                           target="_blank" 
                           class="btn btn-info">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Lihat di Website
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Content -->
                            <div class="mb-4">
                                <h1 class="mb-3">{{ $page->title }}</h1>
                                
                                @if($page->excerpt)
                                    <div class="alert alert-info">
                                        <strong>Ringkasan:</strong> {{ $page->excerpt }}
                                    </div>
                                @endif

                                <div class="content-preview">
                                    {!! $page->content !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Status -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Status Publikasi</h5>
                                </div>
                                <div class="card-body">
                                    @if($page->status === 'published')
                                        <span class="badge bg-success fs-6 mb-2">
                                            <i class="fas fa-eye me-1"></i>
                                            Dipublikasi
                                        </span>
                                    @else
                                        <span class="badge bg-warning fs-6 mb-2">
                                            <i class="fas fa-eye-slash me-1"></i>
                                            Draft
                                        </span>
                                    @endif

                                    <br>

                                    @if($page->show_in_menu)
                                        <span class="badge bg-info fs-6">
                                            <i class="fas fa-bars me-1"></i>
                                            Tampil di Menu (Urutan: {{ $page->menu_order }})
                                        </span>
                                    @else
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-times me-1"></i>
                                            Tidak di Menu
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- URL Info -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">URL Halaman</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Slug:</strong> <code>{{ $page->slug }}</code>
                                    </div>
                                    <div class="mb-2">
                                        <strong>URL:</strong><br>
                                        <a href="{{ route('halaman.tampil', $page->slug) }}" 
                                           target="_blank" 
                                           class="text-break">
                                            {{ route('halaman.tampil', $page->slug) }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Info -->
                            @if($page->meta_title || $page->meta_description || $page->meta_keywords)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($page->meta_title)
                                            <div class="mb-2">
                                                <strong>Meta Title:</strong><br>
                                                <small class="text-muted">{{ $page->meta_title }}</small>
                                            </div>
                                        @endif

                                        @if($page->meta_description)
                                            <div class="mb-2">
                                                <strong>Meta Description:</strong><br>
                                                <small class="text-muted">{{ $page->meta_description }}</small>
                                            </div>
                                        @endif

                                        @if($page->meta_keywords)
                                            <div class="mb-2">
                                                <strong>Meta Keywords:</strong><br>
                                                <small class="text-muted">{{ $page->meta_keywords }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Creation Info -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informasi Halaman</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Dibuat:</strong><br>
                                        <small class="text-muted">{{ $page->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Oleh:</strong><br>
                                        <small class="text-muted">{{ $page->creator->name }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Terakhir diubah:</strong><br>
                                        <small class="text-muted">{{ $page->updated_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.halaman.indeks') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Daftar
                        </a>
                        <div>
                            <a href="{{ route('admin.halaman.edit', $page) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>
                                Edit Halaman
                            </a>
                            <form action="{{ route('admin.halaman.hapus', $page) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus halaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-preview {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1.5rem;
    background-color: #f8f9fa;
    min-height: 300px;
}

.content-preview h1, .content-preview h2, .content-preview h3,
.content-preview h4, .content-preview h5, .content-preview h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.content-preview h1:first-child, .content-preview h2:first-child,
.content-preview h3:first-child, .content-preview h4:first-child,
.content-preview h5:first-child, .content-preview h6:first-child {
    margin-top: 0;
}

.content-preview p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.content-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
    margin: 1rem 0;
}

.content-preview ul, .content-preview ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.content-preview blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6c757d;
}

.content-preview table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
}

.content-preview table th,
.content-preview table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
}

.content-preview table th {
    background-color: #e9ecef;
    font-weight: bold;
}
</style>
@endpush
