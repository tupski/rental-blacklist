<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::active()
                          ->orderBy('sort_order')
                          ->orderBy('name')
                          ->get();

        return view('sponsors.index', compact('sponsors'));
    }

    public function sponsorship()
    {
        return view('sponsors.sponsorship');
    }
}
