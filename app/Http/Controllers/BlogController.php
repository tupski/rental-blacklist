<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display blog index page
     */
    public function index(Request $request)
    {
        $categories = BlogCategory::active()->ordered()->get();
        
        $query = BlogPost::with(['category', 'author'])
            ->published()
            ->latest('published_at');

        // Filter by category if specified
        if ($request->has('kategori') && $request->kategori) {
            $category = BlogCategory::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search functionality
        if ($request->has('cari') && $request->cari) {
            $searchTerm = $request->cari;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        $posts = $query->paginate(12);
        
        // Get featured posts (most viewed in last 30 days)
        $featuredPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderBy('views_count', 'desc')
            ->limit(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display blog post by category and slug
     */
    public function show($kategori, $slug)
    {
        // Find category
        $category = BlogCategory::where('slug', $kategori)->firstOrFail();
        
        // Find post
        $post = BlogPost::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('category_id', $category->id)
            ->published()
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Get related posts
        $relatedPosts = BlogPost::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->where('id', '!=', $post->id)
            ->published()
            ->latest('published_at')
            ->limit(4)
            ->get();

        // Get recent posts
        $recentPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Get all categories for sidebar
        $categories = BlogCategory::active()->ordered()->get();

        return view('blog.show', compact('post', 'relatedPosts', 'recentPosts', 'categories'));
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::active()->ordered()->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    /**
     * Search blog posts
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:100'
        ]);

        $searchTerm = $request->q;
        
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                      ->orWhere('content', 'like', "%{$searchTerm}%")
                      ->orWhere('seo_keywords', 'like', "%{$searchTerm}%");
            })
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::active()->ordered()->get();

        return view('blog.search', compact('posts', 'searchTerm', 'categories'));
    }

    /**
     * Get blog sitemap data
     */
    public function sitemap()
    {
        $posts = BlogPost::with('category')
            ->published()
            ->select('slug', 'category_id', 'updated_at')
            ->get();

        $categories = BlogCategory::active()
            ->select('slug', 'updated_at')
            ->get();

        return response()->view('blog.sitemap', compact('posts', 'categories'))
            ->header('Content-Type', 'text/xml');
    }
}
