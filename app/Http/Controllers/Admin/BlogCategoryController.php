<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display categories list
     */
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->ordered()->get();
        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.blog.categories.create');
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $data = $request->all();

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (BlogCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        BlogCategory::create($data);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Kategori berhasil dibuat!');
    }

    /**
     * Show edit form
     */
    public function edit(BlogCategory $category)
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $data = $request->all();

        // Handle slug
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
            
            // Ensure unique slug
            $originalSlug = $data['slug'];
            $counter = 1;
            while (BlogCategory::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $category->update($data);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Delete category
     */
    public function destroy(BlogCategory $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.blog.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel!');
        }

        $category->delete();

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Generate slug from name
     */
    public function generateSlug(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer'
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        $categoryId = $request->category_id;

        while (BlogCategory::where('slug', $slug)->when($categoryId, function($query) use ($categoryId) {
            return $query->where('id', '!=', $categoryId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return response()->json(['slug' => $slug]);
    }
}
