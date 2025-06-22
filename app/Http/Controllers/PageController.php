<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    /**
     * Display the specified page by slug
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                   ->where('status', 'published')
                   ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
