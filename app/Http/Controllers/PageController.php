<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;

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

    /**
     * Display the terms of service page.
     */
    public function termsOfService()
    {
        $content = Setting::get('terms_of_service', '<h2>Syarat dan Ketentuan</h2><p>Konten syarat dan ketentuan belum diatur.</p>');

        return view('pages.terms-of-service', compact('content'));
    }

    /**
     * Display the privacy policy page.
     */
    public function privacyPolicy()
    {
        $content = Setting::get('privacy_policy', '<h2>Kebijakan Privasi</h2><p>Konten kebijakan privasi belum diatur.</p>');

        return view('pages.privacy-policy', compact('content'));
    }
}
