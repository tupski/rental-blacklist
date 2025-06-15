<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function generate(Request $request)
    {
        $user = Auth::user();

        // Check if user can use API
        if (!$user->canUseApi()) {
            $message = 'Akses API ditolak.';
            if (!$user->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan API.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk dapat menggunakan API.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'account_status' => $user->account_status,
                'email_verified' => $user->hasVerifiedEmail()
            ], 403);
        }

        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $name = $request->input('name', 'Default API Key');
        $apiKey = $user->createApiKey($name);

        return response()->json([
            'success' => true,
            'message' => 'API Key berhasil dibuat',
            'api_key' => $apiKey->key
        ]);
    }

    public function reset()
    {
        $user = Auth::user();

        // Check if user can use API
        if (!$user->canUseApi()) {
            $message = 'Akses API ditolak.';
            if (!$user->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan API.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk dapat menggunakan API.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'account_status' => $user->account_status,
                'email_verified' => $user->hasVerifiedEmail()
            ], 403);
        }

        $apiKey = $user->createApiKey('Default API Key');

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
