@extends('layouts.main')

@section('title', $category->meta_title ?: $category->name)

@section('meta')
<meta name="description" content="{{ $category->meta_description ?: $category->description }}">
<meta name="keywords" content="kategori {{ strtolower($category->name) }}, blog rental, artikel rental">

<!-- Open Graph -->
<meta property="og:title" content="{{ $category->meta_title ?: $category->name }} - CekPenyewa.com">
<meta property="og:description" content="{{ $category->meta_description ?: $category->description }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('blog.kategori', $category->slug) }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ route('blog.kategori', $category->slug) }}">
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.indeks') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-folder-open me-2"></i>
                    {{ $category->name }}
                </h1>
                @if($category->description)
                <p class="lead text-muted">
                    {{ $category->description }}
                </p>
                @endif
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">{{ $posts->total() }} artikel</span>
                </div>
            </div>

            <!-- Back to All Categories -->
            <div class="text-center mb-4">
                <a href="{{ route('blog.indeks') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Semua Artikel
                </a>
            </div>

            <!-- Categories Navigation -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('blog.indeks') }}" 
                           class="btn btn-outline-primary btn-sm">
                            Semua
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.kategori', $cat->slug) }}" 
                               class="btn {{ $cat->id == $category->id ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                {{ $cat->name }}
                                <span class="badge bg-light text-dark ms-1">{{ $cat->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles -->
    <div class="row">
        <div class="col-12">
            @if($posts->count() > 0)
                <div class="row">
                    @foreach($posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="card hover-card h-100">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $post->title }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    <small class="text-muted ms-2">
                                        <i class="fas fa-eye me-1"></i>{{ number_format($post->views_count) }}
                                    </small>
                                </div>
                                <h5 class="card-title">
                                    <a href="{{ $post->url }}" class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $post->formatted_published_date }}
                                        </small>
                                    </div>
                                    @if($post->reading_time)
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $post->reading_time }} menit baca
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada artikel di kategori ini</h4>
                    <p class="text-muted">
                        Artikel untuk kategori "{{ $category->name }}" belum tersedia.
                    </p>
                    <a href="{{ route('blog.indeks') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Lihat Semua Artikel
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Categories -->
    @if($categories->count() > 1)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="h4 fw-bold mb-3">
                <i class="fas fa-folder me-2"></i>
                Kategori Lainnya
            </h3>
            <div class="row">
                @foreach($categories->where('id', '!=', $category->id)->take(3) as $relatedCategory)
                <div class="col-md-4 mb-3">
                    <div class="card hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-folder fa-2x text-primary"></i>
                            </div>
                            <h5 class="card-title">
                                <a href="{{ route('blog.kategori', $relatedCategory->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $relatedCategory->name }}
                                </a>
                            </h5>
                            @if($relatedCategory->description)
                            <p class="card-text text-muted small">
                                {{ Str::limit($relatedCategory->description, 100) }}
                            </p>
                            @endif
                            <div class="mt-3">
                                <span class="badge bg-primary">{{ $relatedCategory->posts_count }} artikel</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-scroll to articles after category selection
    if (window.location.hash === '#articles') {
        $('html, body').animate({
            scrollTop: $('.row:has(.card)').offset().top - 100
        }, 500);
    }
});
</script>
@endpush

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin: 0.125rem;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .btn-group .btn {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>
@endpush
