<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class LegalSettingController extends Controller
{
    /**
     * Display the legal settings page.
     */
    public function index()
    {
        $settings = Setting::whereIn('group', ['legal'])
            ->orderBy('label')
            ->get()
            ->groupBy('group');

        return view('admin.settings.legal', compact('settings'));
    }

    /**
     * Update the legal settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan halaman legal berhasil disimpan!');
    }
}
