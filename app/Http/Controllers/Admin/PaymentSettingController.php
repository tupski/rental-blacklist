<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('group', ['payment', 'pricing'])
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');

        return view('admin.settings.payment', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan pembayaran berhasil disimpan!');
    }
}
