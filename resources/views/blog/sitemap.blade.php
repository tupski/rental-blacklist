<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Blog Index -->
    <url>
        <loc>{{ route('blog.indeks') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- Blog Categories -->
    @foreach($categories as $category)
    <url>
        <loc>{{ route('blog.kategori', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toISOString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    
    <!-- Blog Posts -->
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.detail', ['kategori' => $post->category->slug, 'slug' => $post->slug]) }}</loc>
        <lastmod>{{ $post->updated_at->toISOString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
