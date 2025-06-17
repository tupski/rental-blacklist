<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display blog posts list
     */
    public function index(Request $request)
    {
        $categories = BlogCategory::active()->ordered()->get();

        if ($request->ajax()) {
            return $this->getData($request);
        }

        return view('admin.blog.index', compact('categories'));
    }

    /**
     * Get blog posts data for AJAX
     */
    public function getData(Request $request)
    {
        $query = BlogPost::with(['category', 'author']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        // Per page
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $posts = $query->latest()->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'from' => $posts->firstItem(),
                    'to' => $posts->lastItem(),
                    'links' => $posts->links()->render()
                ]
            ]);
        }

        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Store new blog post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url'
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (BlogPost::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '-' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blog/images', $filename, 'public');
            $data['featured_image'] = $path;
        }

        // Handle published_at
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = BlogPost::create($data);

        // Calculate reading time
        $post->calculateReadingTime();

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Blog post berhasil dibuat!');
    }

    /**
     * Show edit form
     */
    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blog.edit', compact('post', 'categories'));
    }

    /**
     * Update blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url'
        ]);

        $data = $request->all();

        // Handle slug
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);

            // Ensure unique slug
            $originalSlug = $data['slug'];
            $counter = 1;
            while (BlogPost::where('slug', $data['slug'])->where('id', '!=', $post->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = time() . '-' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blog/images', $filename, 'public');
            $data['featured_image'] = $path;
        }

        // Handle published_at
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post->update($data);

        // Recalculate reading time
        $post->calculateReadingTime();

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Blog post berhasil diperbarui!');
    }

    /**
     * Delete blog post
     */
    public function destroy(BlogPost $post)
    {
        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post berhasil dihapus!');
    }

    /**
     * Auto-save functionality
     */
    public function autoSave(Request $request, BlogPost $post)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500'
        ]);

        $data = $request->only(['title', 'content', 'excerpt']);
        $post->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Draft tersimpan otomatis',
            'saved_at' => now()->format('H:i:s')
        ]);
    }

    /**
     * Generate slug from title
     */
    public function generateSlug(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'post_id' => 'nullable|integer'
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        $postId = $request->post_id;

        while (BlogPost::where('slug', $slug)->when($postId, function($query) use ($postId) {
            return $query->where('id', '!=', $postId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return response()->json(['slug' => $slug]);
    }

    /**
     * Analyze SEO score
     */
    public function analyzeSeo(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string'
        ]);

        $analysis = $this->performSeoAnalysis($request->all());

        return response()->json($analysis);
    }

    /**
     * Perform SEO analysis
     */
    private function performSeoAnalysis($data)
    {
        $score = 0;
        $issues = [];
        $suggestions = [];

        // Title analysis
        $titleLength = strlen($data['title']);
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 20;
        } else {
            $issues[] = 'Judul sebaiknya 30-60 karakter';
            $suggestions[] = $titleLength < 30 ? 'Perpanjang judul' : 'Persingkat judul';
        }

        // Content analysis
        $wordCount = str_word_count(strip_tags($data['content']));
        if ($wordCount >= 300) {
            $score += 25;
        } else {
            $issues[] = 'Konten terlalu pendek (minimal 300 kata)';
            $suggestions[] = 'Tambahkan lebih banyak konten berkualitas';
        }

        // Meta description analysis
        if (!empty($data['seo_description'])) {
            $metaLength = strlen($data['seo_description']);
            if ($metaLength >= 120 && $metaLength <= 160) {
                $score += 20;
            } else {
                $issues[] = 'Meta description sebaiknya 120-160 karakter';
            }
        } else {
            $issues[] = 'Meta description belum diisi';
            $suggestions[] = 'Tambahkan meta description yang menarik';
        }

        // Keywords analysis
        if (!empty($data['seo_keywords'])) {
            $score += 15;
        } else {
            $issues[] = 'SEO keywords belum diisi';
            $suggestions[] = 'Tambahkan kata kunci yang relevan';
        }

        // Headings analysis (check for H1, H2, etc.)
        $headingCount = preg_match_all('/<h[1-6][^>]*>/i', $data['content']);
        if ($headingCount > 0) {
            $score += 20;
        } else {
            $issues[] = 'Tidak ada heading dalam konten';
            $suggestions[] = 'Gunakan heading (H1, H2, H3) untuk struktur yang baik';
        }

        return [
            'score' => min($score, 100),
            'issues' => $issues,
            'suggestions' => $suggestions,
            'word_count' => $wordCount,
            'reading_time' => ceil($wordCount / 200)
        ];
    }

    /**
     * Upload image for TinyMCE editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $filename = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blog/content', $filename, 'public');

            return response()->json([
                'location' => Storage::url($path)
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
