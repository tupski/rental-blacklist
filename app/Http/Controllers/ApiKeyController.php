<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $name = $request->input('name', 'Default API Key');
        $apiKey = Auth::user()->createApiKey($name);

        return response()->json([
            'success' => true,
            'message' => 'API Key berhasil dibuat',
            'api_key' => $apiKey->key
        ]);
    }

    public function reset()
    {
        $apiKey = Auth::user()->createApiKey('Default API Key');

        return response()->json([
            'success' => true,
            'message' => 'API Key berhasil direset',
            'api_key' => $apiKey->key
        ]);
    }

    public function show()
    {
        $apiKey = Auth::user()->getActiveApiKey();

        return response()->json([
            'success' => true,
            'api_key' => $apiKey ? $apiKey->key : null,
            'last_used' => $apiKey ? $apiKey->last_used_at : null
        ]);
    }
}
