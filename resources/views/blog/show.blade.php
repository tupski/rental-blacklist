@extends('layouts.main')

@section('title', $post->seo_title ?: $post->title)

@section('meta')
<meta name="description" content="{{ $post->seo_description ?: $post->excerpt }}">
<meta name="keywords" content="{{ $post->seo_keywords }}">
<meta name="author" content="{{ $post->author->name }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
<meta property="og:description" content="{{ $post->seo_description ?: $post->excerpt }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ $post->url }}">
@if($post->featured_image)
<meta property="og:image" content="{{ Storage::url($post->featured_image) }}">
@endif
<meta property="article:published_time" content="{{ $post->published_at->toISOString() }}">
<meta property="article:author" content="{{ $post->author->name }}">
<meta property="article:section" content="{{ $post->category->name }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->seo_title ?: $post->title }}">
<meta name="twitter:description" content="{{ $post->seo_description ?: $post->excerpt }}">
@if($post->featured_image)
<meta name="twitter:image" content="{{ Storage::url($post->featured_image) }}">
@endif

<!-- Canonical URL -->
@if($post->canonical_url)
<link rel="canonical" href="{{ $post->canonical_url }}">
@else
<link rel="canonical" href="{{ $post->url }}">
@endif

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "description": "{{ $post->excerpt }}",
  "image": "{{ $post->featured_image ? Storage::url($post->featured_image) : '' }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "CekPenyewa.com",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "datePublished": "{{ $post->published_at->toISOString() }}",
  "dateModified": "{{ $post->updated_at->toISOString() }}",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ $post->url }}"
  }
}
</script>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.indeks') }}">Blog</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.kategori', $post->category->slug) }}">{{ $post->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="card shadow-sm">
                <!-- Featured Image -->
                @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" 
                     class="card-img-top" 
                     style="height: 400px; object-fit: cover;"
                     alt="{{ $post->title }}">
                @endif

                <div class="card-body">
                    <!-- Article Meta -->
                    <div class="mb-3">
                        <a href="{{ route('blog.kategori', $post->category->slug) }}" 
                           class="badge bg-primary text-decoration-none me-2">
                            {{ $post->category->name }}
                        </a>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $post->formatted_published_date }}
                        </small>
                        <small class="text-muted ms-3">
                            <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                        </small>
                        <small class="text-muted ms-3">
                            <i class="fas fa-eye me-1"></i>{{ number_format($post->views_count) }} views
                        </small>
                        @if($post->reading_time)
                        <small class="text-muted ms-3">
                            <i class="fas fa-clock me-1"></i>{{ $post->reading_time }} menit baca
                        </small>
                        @endif
                    </div>

                    <!-- Article Title -->
                    <h1 class="card-title display-5 fw-bold mb-4">{{ $post->title }}</h1>

                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $post->content !!}
                    </div>

                    <!-- Article Footer -->
                    <hr class="my-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $post->author->name }}</h6>
                                    <small class="text-muted">Penulis</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="d-flex justify-content-md-end gap-2">
                                <!-- Share Buttons -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . $post->url) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm" 
                                        onclick="copyToClipboard('{{ $post->url }}')">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="mt-5">
                <h3 class="h4 fw-bold mb-3">
                    <i class="fas fa-newspaper me-2"></i>
                    Artikel Terkait
                </h3>
                <div class="row">
                    @foreach($relatedPosts as $relatedPost)
                    <div class="col-md-6 mb-3">
                        <div class="card hover-card h-100">
                            @if($relatedPost->featured_image)
                            <img src="{{ Storage::url($relatedPost->featured_image) }}" 
                                 class="card-img-top" 
                                 style="height: 150px; object-fit: cover;"
                                 alt="{{ $relatedPost->title }}">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ $relatedPost->url }}" class="text-decoration-none text-dark">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h6>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($relatedPost->excerpt, 100) }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $relatedPost->formatted_published_date }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Posts -->
            @if($recentPosts->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Artikel Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($recentPosts as $recentPost)
                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        @if($recentPost->featured_image)
                        <img src="{{ Storage::url($recentPost->featured_image) }}" 
                             class="me-3 rounded" 
                             style="width: 60px; height: 60px; object-fit: cover;"
                             alt="{{ $recentPost->title }}">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="{{ $recentPost->url }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($recentPost->title, 60) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $recentPost->formatted_published_date }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            @if($categories->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-folder me-2"></i>
                        Kategori
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($categories as $category)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <a href="{{ route('blog.kategori', $category->slug) }}" 
                           class="text-decoration-none {{ $category->id == $post->category_id ? 'fw-bold text-primary' : 'text-dark' }}">
                            {{ $category->name }}
                        </a>
                        <span class="badge bg-light text-dark">{{ $category->posts_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>Link berhasil disalin!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    });
}

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.article-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.article-content blockquote {
    border-left: 4px solid var(--primary-color);
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

.article-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.9em;
}

.article-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
}
</style>
@endpush
