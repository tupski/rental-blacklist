<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with('creator')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Page::generateSlug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
            // Check if slug already exists
            if (Page::where('slug', $data['slug'])->exists()) {
                return back()->withErrors(['slug' => 'Slug sudah digunakan.'])->withInput();
            }
        }

        $data['created_by'] = auth()->id();
        $data['show_in_menu'] = $request->has('show_in_menu');

        Page::create($data);

        return redirect()->route('admin.halaman.indeks')
                        ->with('success', 'Halaman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Page::generateSlug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
            // Check if slug already exists (except current page)
            if (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
                return back()->withErrors(['slug' => 'Slug sudah digunakan.'])->withInput();
            }
        }

        $data['show_in_menu'] = $request->has('show_in_menu');

        $page->update($data);

        return redirect()->route('admin.halaman.indeks')
                        ->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.halaman.indeks')
                        ->with('success', 'Halaman berhasil dihapus.');
    }
}
