<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SponsorPackage;

class SponsorPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = SponsorPackage::orderBy('sort_order')
                                 ->orderBy('name')
                                 ->paginate(10);

        return view('admin.sponsor-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sponsor-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'benefits' => 'required|array|min:1',
            'benefits.*' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'placement_options' => 'required|array|min:1',
            'placement_options.*' => 'required|string|in:home_top,home_bottom,footer,sidebar',
            'max_logo_size_kb' => 'nullable|integer|min:100|max:10240',
            'recommended_logo_size' => 'nullable|string|max:50',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000'
        ]);

        $data = $request->all();
        $data['benefits'] = array_filter($data['benefits']); // Remove empty benefits
        $data['is_popular'] = $request->has('is_popular');
        $data['is_active'] = $request->has('is_active');

        SponsorPackage::create($data);

        return redirect()->route('admin.paket-sponsor.indeks')
                        ->with('success', 'Paket sponsor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SponsorPackage $sponsorPackage)
    {
        return view('admin.sponsor-packages.show', compact('sponsorPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SponsorPackage $sponsorPackage)
    {
        return view('admin.sponsor-packages.edit', compact('sponsorPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SponsorPackage $sponsorPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'benefits' => 'required|array|min:1',
            'benefits.*' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'placement_options' => 'required|array|min:1',
            'placement_options.*' => 'required|string|in:home_top,home_bottom,footer,sidebar',
            'max_logo_size_kb' => 'nullable|integer|min:100|max:10240',
            'recommended_logo_size' => 'nullable|string|max:50',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000'
        ]);

        $data = $request->all();
        $data['benefits'] = array_filter($data['benefits']); // Remove empty benefits
        $data['is_popular'] = $request->has('is_popular');
        $data['is_active'] = $request->has('is_active');

        $sponsorPackage->update($data);

        return redirect()->route('admin.paket-sponsor.indeks')
                        ->with('success', 'Paket sponsor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SponsorPackage $sponsorPackage)
    {
        // Cek apakah ada pembelian yang masih aktif
        $activePurchases = $sponsorPackage->purchases()
                                         ->where('payment_status', 'confirmed')
                                         ->where('expires_at', '>', now())
                                         ->count();

        if ($activePurchases > 0) {
            return redirect()->route('admin.paket-sponsor.indeks')
                            ->with('error', 'Tidak dapat menghapus paket sponsor yang masih memiliki pembelian aktif.');
        }

        $sponsorPackage->delete();

        return redirect()->route('admin.paket-sponsor.indeks')
                        ->with('success', 'Paket sponsor berhasil dihapus.');
    }
}
