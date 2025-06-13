<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::orderBy('sort_order')
                          ->orderBy('name')
                          ->paginate(10);

        return view('admin.sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('admin.sponsors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'required|url',
            'description' => 'nullable|string',
            'position' => 'required|in:home_top,home_bottom,footer',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $data = $request->all();

        // Upload logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        Sponsor::create($data);

        return redirect()->route('admin.sponsors.index')
                        ->with('success', 'Sponsor berhasil ditambahkan');
    }

    public function show(Sponsor $sponsor)
    {
        return view('admin.sponsors.show', compact('sponsor'));
    }

    public function edit(Sponsor $sponsor)
    {
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'required|url',
            'description' => 'nullable|string',
            'position' => 'required|in:home_top,home_bottom,footer',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $data = $request->all();

        // Upload logo baru jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $data['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        $sponsor->update($data);

        return redirect()->route('admin.sponsors.index')
                        ->with('success', 'Sponsor berhasil diperbarui');
    }

    public function destroy(Sponsor $sponsor)
    {
        // Hapus logo
        if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
            Storage::disk('public')->delete($sponsor->logo);
        }

        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')
                        ->with('success', 'Sponsor berhasil dihapus');
    }
}
