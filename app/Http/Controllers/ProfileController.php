<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Use different view for admin
        if ($user->role === 'admin') {
            return view('admin.profile.edit', [
                'user' => $user,
            ]);
        }

        // Check if user can edit profile
        $canEdit = $user->canEditProfile();
        $requiresEmailVerification = $user->requiresEmailVerification();

        return view('profile.edit', [
            'user' => $user,
            'canEdit' => $canEdit,
            'requiresEmailVerification' => $requiresEmailVerification,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Check if user can edit profile
        if (!$user->canEditProfile()) {
            $message = 'Anda tidak dapat mengedit profil saat ini.';
            if (!$user->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat mengedit profil.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk dapat mengedit profil.';
            }

            return Redirect::route('profil.edit')->with('error', $message);
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profil.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Verify user password for viewing legalitas documents.
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password benar'
        ]);
    }
}
