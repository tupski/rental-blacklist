<?php

namespace App\Http\Controllers;

use App\Models\DocumentVerification;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    public function index()
    {
        return view('verification.index');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|max:32'
        ]);

        $verification = DocumentVerification::with(['blacklist', 'user'])
            ->where('verification_code', strtoupper($request->verification_code))
            ->first();

        if (!$verification) {
            return back()->with('error', 'Kode verifikasi tidak ditemukan atau tidak valid.');
        }

        // Mark as verified if not already
        if (!$verification->isVerified()) {
            $verification->markAsVerified();
        }

        return view('verification.result', compact('verification'));
    }
}
