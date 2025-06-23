<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavbarMenu;
use Illuminate\Http\Request;

class NavbarMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = NavbarMenu::with('parent', 'children')
                          ->main()
                          ->ordered()
                          ->get();

        return view('admin.navbar-menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentMenus = NavbarMenu::main()->active()->ordered()->get();
        return view('admin.navbar-menus.create', compact('parentMenus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required_without:route_name|nullable|string|max:255',
            'route_name' => 'required_without:url|nullable|string|max:255',
            'route_params' => 'nullable|json',
            'icon' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'visibility' => 'required|in:all,guest,auth,admin,rental',
            'parent_id' => 'nullable|exists:navbar_menus,id',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['open_new_tab'] = $request->has('open_new_tab');

        // Parse route params if provided
        if ($data['route_params']) {
            $data['route_params'] = json_decode($data['route_params'], true);
        }

        NavbarMenu::create($data);

        return redirect()->route('admin.menu-navbar.indeks')
                        ->with('success', 'Menu navbar berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NavbarMenu $menu)
    {
        return view('admin.navbar-menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NavbarMenu $menu)
    {
        $parentMenus = NavbarMenu::main()
                                ->active()
                                ->where('id', '!=', $menu->id)
                                ->ordered()
                                ->get();

        return view('admin.navbar-menus.edit', compact('menu', 'parentMenus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NavbarMenu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required_without:route_name|nullable|string|max:255',
            'route_name' => 'required_without:url|nullable|string|max:255',
            'route_params' => 'nullable|json',
            'icon' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'visibility' => 'required|in:all,guest,auth,admin,rental',
            'parent_id' => 'nullable|exists:navbar_menus,id',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['open_new_tab'] = $request->has('open_new_tab');

        // Parse route params if provided
        if ($data['route_params']) {
            $data['route_params'] = json_decode($data['route_params'], true);
        }

        $menu->update($data);

        return redirect()->route('admin.menu-navbar.indeks')
                        ->with('success', 'Menu navbar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NavbarMenu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menu-navbar.indeks')
                        ->with('success', 'Menu navbar berhasil dihapus.');
    }

    /**
     * Toggle menu status
     */
    public function toggleStatus(NavbarMenu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status menu berhasil diubah.',
            'is_active' => $menu->is_active
        ]);
    }

    /**
     * Update menu order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:navbar_menus,id',
            'menus.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->menus as $menuData) {
            NavbarMenu::where('id', $menuData['id'])
                     ->update(['order' => $menuData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan menu berhasil diperbarui.'
        ]);
    }
}
