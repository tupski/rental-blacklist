@extends('layouts.main')

@section('title', 'Blog')

@section('meta')
<meta name="description" content="Blog CekPenyewa.com - Artikel dan tips seputar rental kendaraan, blacklist, dan keamanan bisnis rental di Indonesia.">
<meta name="keywords" content="blog rental, tips rental, blacklist rental, keamanan rental, bisnis rental">
<meta property="og:title" content="Blog - CekPenyewa.com">
<meta property="og:description" content="Blog CekPenyewa.com - Artikel dan tips seputar rental kendaraan, blacklist, dan keamanan bisnis rental di Indonesia.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('blog.indeks') }}">
@endsection

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-blog me-2"></i>
                    Blog CekPenyewa
                </h1>
                <p class="lead text-muted">
                    Artikel dan tips seputar rental kendaraan, blacklist, dan keamanan bisnis rental
                </p>
            </div>

            <!-- Search Form -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <form action="{{ route('blog.cari') }}" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control form-control-lg me-2" 
                               placeholder="Cari artikel..." 
                               value="{{ request('q') }}"
                               required>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Categories Filter -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('blog.indeks') }}" 
                           class="btn {{ !request('kategori') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            Semua
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('blog.kategori', $category->slug) }}" 
                               class="btn {{ request('kategori') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                {{ $category->name }}
                                <span class="badge bg-light text-dark ms-1">{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Posts -->
    @if($featuredPosts->count() > 0 && !request('cari') && !request('kategori'))
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="h4 fw-bold mb-3">
                <i class="fas fa-star text-warning me-2"></i>
                Artikel Populer
            </h2>
            <div class="row">
                @foreach($featuredPosts as $post)
                <div class="col-md-4 mb-3">
                    <div class="card hover-card h-100">
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
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $post->formatted_published_date }}
                                    @if($post->reading_time)
                                        <i class="fas fa-clock ms-2 me-1"></i>{{ $post->reading_time }} menit
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Blog Posts -->
    <div class="row">
        <div class="col-12">
            @if(request('cari'))
                <h2 class="h4 fw-bold mb-3">
                    Hasil pencarian untuk: "{{ request('cari') }}"
                    <small class="text-muted">({{ $posts->total() }} artikel)</small>
                </h2>
            @elseif(request('kategori'))
                <h2 class="h4 fw-bold mb-3">
                    Kategori: {{ $posts->first()->category->name ?? 'Tidak ditemukan' }}
                </h2>
            @else
                <h2 class="h4 fw-bold mb-3">
                    <i class="fas fa-newspaper me-2"></i>
                    Artikel Terbaru
                </h2>
            @endif

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
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada artikel ditemukan</h4>
                    <p class="text-muted">
                        @if(request('cari'))
                            Coba gunakan kata kunci yang berbeda
                        @else
                            Belum ada artikel yang dipublikasikan
                        @endif
                    </p>
                    <a href="{{ route('blog.indeks') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Blog
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-scroll to results after search
    @if(request('cari') || request('kategori'))
        $('html, body').animate({
            scrollTop: $('.row:has(.card)').offset().top - 100
        }, 500);
    @endif
});
</script>
@endpush
