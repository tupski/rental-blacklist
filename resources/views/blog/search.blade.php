@extends('layouts.main')

@section('title', 'Hasil Pencarian: ' . $searchTerm)

@section('meta')
<meta name="description" content="Hasil pencarian blog untuk '{{ $searchTerm }}' di CekPenyewa.com. Temukan artikel seputar rental, blacklist, dan keamanan bisnis rental.">
<meta name="keywords" content="pencarian blog, {{ $searchTerm }}, artikel rental, blog cekpenyewa">
<meta name="robots" content="noindex, follow">
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.indeks') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
        </ol>
    </nav>

    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold text-primary mb-3">
                    <i class="fas fa-search me-2"></i>
                    Hasil Pencarian
                </h1>
                <p class="lead text-muted">
                    Menampilkan hasil untuk: <strong>"{{ $searchTerm }}"</strong>
                </p>
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">{{ $posts->total() }} artikel ditemukan</span>
                </div>
            </div>

            <!-- Search Form -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <form action="{{ route('blog.cari') }}" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control form-control-lg me-2" 
                               placeholder="Cari artikel lainnya..." 
                               value="{{ $searchTerm }}"
                               required>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="text-center mb-4">
                <a href="{{ route('blog.indeks') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Blog
                </a>
                <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                    <i class="fas fa-times me-2"></i>
                    Hapus Pencarian
                </button>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        <div class="col-lg-8">
            @if($posts->count() > 0)
                <!-- Results Info -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">
                        Hasil {{ $posts->firstItem() }}-{{ $posts->lastItem() }} dari {{ $posts->total() }}
                    </h3>
                    <small class="text-muted">
                        Diurutkan berdasarkan relevansi
                    </small>
                </div>

                <!-- Articles List -->
                <div class="row">
                    @foreach($posts as $post)
                    <div class="col-12 mb-4">
                        <article class="card hover-card">
                            <div class="row g-0">
                                @if($post->featured_image)
                                <div class="col-md-4">
                                    <img src="{{ Storage::url($post->featured_image) }}" 
                                         class="img-fluid rounded-start h-100" 
                                         style="object-fit: cover; min-height: 200px;"
                                         alt="{{ $post->title }}">
                                </div>
                                <div class="col-md-8">
                                @else
                                <div class="col-12">
                                @endif
                                    <div class="card-body h-100 d-flex flex-column">
                                        <div class="mb-2">
                                            <a href="{{ route('blog.kategori', $post->category->slug) }}" 
                                               class="badge bg-primary text-decoration-none">
                                                {{ $post->category->name }}
                                            </a>
                                            <small class="text-muted ms-2">
                                                <i class="fas fa-eye me-1"></i>{{ number_format($post->views_count) }}
                                            </small>
                                        </div>
                                        
                                        <h5 class="card-title">
                                            <a href="{{ $post->url }}" class="text-decoration-none text-dark">
                                                {!! highlightSearchTerm($post->title, $searchTerm) !!}
                                            </a>
                                        </h5>
                                        
                                        <p class="card-text text-muted flex-grow-1">
                                            {!! highlightSearchTerm($post->excerpt, $searchTerm) !!}
                                        </p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-calendar me-1"></i>{{ $post->formatted_published_date }}
                                                </small>
                                                @if($post->reading_time)
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $post->reading_time }} menit
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->appends(['q' => $searchTerm])->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada hasil ditemukan</h4>
                    <p class="text-muted">
                        Tidak ada artikel yang cocok dengan pencarian "<strong>{{ $searchTerm }}</strong>".
                    </p>
                    
                    <!-- Search Suggestions -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Saran pencarian:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">• Periksa ejaan kata kunci</li>
                            <li class="mb-2">• Gunakan kata kunci yang lebih umum</li>
                            <li class="mb-2">• Coba kata kunci yang berbeda</li>
                            <li class="mb-2">• Gunakan sinonim atau kata terkait</li>
                        </ul>
                    </div>

                    <!-- Popular Search Terms -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Pencarian populer:</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('blog.cari', ['q' => 'rental mobil']) }}" class="btn btn-outline-primary btn-sm">rental mobil</a>
                            <a href="{{ route('blog.cari', ['q' => 'blacklist']) }}" class="btn btn-outline-primary btn-sm">blacklist</a>
                            <a href="{{ route('blog.cari', ['q' => 'keamanan']) }}" class="btn btn-outline-primary btn-sm">keamanan</a>
                            <a href="{{ route('blog.cari', ['q' => 'tips rental']) }}" class="btn btn-outline-primary btn-sm">tips rental</a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('blog.indeks') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Semua Artikel
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            @if($categories->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-folder me-2"></i>
                        Cari berdasarkan Kategori
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($categories as $category)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <a href="{{ route('blog.kategori', $category->slug) }}" 
                           class="text-decoration-none text-dark">
                            {{ $category->name }}
                        </a>
                        <span class="badge bg-light text-dark">{{ $category->posts_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Search Tips -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Tips Pencarian
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Gunakan kata kunci spesifik
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Coba variasi kata yang berbeda
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Gunakan tanda kutip untuk frasa exact
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Kombinasikan beberapa kata kunci
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearSearch() {
    window.location.href = '{{ route("blog.indeks") }}';
}

// Highlight search terms in results
function highlightSearchTerm(text, term) {
    if (!term || term.length < 2) return text;
    
    const regex = new RegExp(`(${term})`, 'gi');
    return text.replace(regex, '<mark class="bg-warning">$1</mark>');
}

$(document).ready(function() {
    // Auto-scroll to results
    if ($('.card').length > 0) {
        $('html, body').animate({
            scrollTop: $('.row:has(.card)').offset().top - 100
        }, 500);
    }
    
    // Focus on search input
    $('input[name="q"]').focus().select();
});
</script>
@endpush

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

mark {
    padding: 0.1em 0.2em;
    border-radius: 0.2em;
}

.badge {
    font-size: 0.75em;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 1.75rem;
    }
    
    .card .row.g-0 .col-md-4,
    .card .row.g-0 .col-md-8 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .card img {
        min-height: 150px !important;
    }
}
</style>
@endpush

@php
function highlightSearchTerm($text, $term) {
    if (!$term || strlen($term) < 2) return $text;
    
    $pattern = '/(' . preg_quote($term, '/') . ')/i';
    return preg_replace($pattern, '<mark class="bg-warning">$1</mark>', $text);
}
@endphp
