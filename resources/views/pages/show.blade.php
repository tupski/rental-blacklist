@extends('layouts.main')

@section('title', $page->meta_title ?: $page->title)

@section('meta')
@if($page->meta_description)
<meta name="description" content="{{ $page->meta_description }}">
@endif
@if($page->meta_keywords)
<meta name="keywords" content="{{ $page->meta_keywords }}">
@endif
<meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
@if($page->meta_description)
<meta property="og:description" content="{{ $page->meta_description }}">
@endif
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('halaman.tampil', $page->slug) }}">
@endsection

@section('content')
<!-- Page Header -->
<section class="py-5" style="background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead opacity-90">{{ $page->excerpt }}</p>
                @endif
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <small class="opacity-75">
                        <i class="fas fa-calendar me-1"></i>
                        Dipublikasi {{ $page->created_at->format('d F Y') }}
                    </small>
                    @if($page->updated_at != $page->created_at)
                        <small class="opacity-75">
                            <i class="fas fa-edit me-1"></i>
                            Diperbarui {{ $page->updated_at->format('d F Y') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="page-content">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-4">
                    <a href="{{ route('beranda') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.page-content {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #333;
}

.page-content h1, .page-content h2, .page-content h3,
.page-content h4, .page-content h5, .page-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #da3544;
    font-weight: 600;
}

.page-content h1:first-child, .page-content h2:first-child,
.page-content h3:first-child, .page-content h4:first-child,
.page-content h5:first-child, .page-content h6:first-child {
    margin-top: 0;
}

.page-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 2rem 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.page-content ul, .page-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.5rem;
}

.page-content blockquote {
    border-left: 4px solid #da3544;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #6c757d;
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
}

.page-content table {
    width: 100%;
    margin-bottom: 2rem;
    border-collapse: collapse;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-content table th,
.page-content table td {
    padding: 1rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.page-content table th {
    background-color: #da3544;
    color: white;
    font-weight: 600;
}

.page-content table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.page-content table tbody tr:hover {
    background-color: #e9ecef;
}

.page-content a {
    color: #da3544;
    text-decoration: none;
    font-weight: 500;
}

.page-content a:hover {
    color: #b02a37;
    text-decoration: underline;
}

.page-content code {
    background-color: #f8f9fa;
    color: #da3544;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.9em;
}

.page-content pre {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 2rem 0;
    border-left: 4px solid #da3544;
}

.page-content pre code {
    background-color: transparent;
    color: #333;
    padding: 0;
}

.page-content hr {
    border: none;
    height: 2px;
    background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
    margin: 3rem 0;
    border-radius: 1px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-content {
        font-size: 1rem;
    }
    
    .page-content table {
        font-size: 0.9rem;
    }
    
    .page-content table th,
    .page-content table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
@endpush
