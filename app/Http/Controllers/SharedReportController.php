<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SharedReport;
use App\Models\RentalBlacklist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class SharedReportController extends Controller
{
    /**
     * Create a shared report link
     */
    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'duration' => 'required|integer|in:1,3,6,12,24',
            'password' => 'required|string|min:6|confirmed',
            'one_time_view' => 'nullable|boolean'
        ], [
            'duration.required' => 'Durasi harus dipilih',
            'duration.in' => 'Durasi tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Additional check for password confirmation
        if ($request->password !== $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'message' => 'Password dan konfirmasi password tidak cocok'
            ], 422);
        }

        $blacklist = RentalBlacklist::findOrFail($id);
        $user = auth()->user();

        // Check if user has permission to share this report
        if (!$this->canShareReport($user, $blacklist)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk membagikan laporan ini'
            ], 403);
        }

        // Create shared report
        $sharedReport = SharedReport::create([
            'token' => SharedReport::generateToken(),
            'blacklist_id' => $id,
            'user_id' => $user->id,
            'password' => Hash::make($request->password),
            'expires_at' => now()->addHours((int) $request->duration),
            'one_time_view' => $request->boolean('one_time_view', false)
        ]);

        $shareUrl = route('shared.view', $sharedReport->token);

        return response()->json([
            'success' => true,
            'share_url' => $shareUrl,
            'expires_at' => $sharedReport->formatted_expiry,
            'one_time_view' => $sharedReport->one_time_view
        ]);
    }

    /**
     * View shared report (password form)
     */
    public function view($token)
    {
        $sharedReport = SharedReport::with(['blacklist', 'user'])
            ->where('token', $token)
            ->firstOrFail();

        if (!$sharedReport->isValid()) {
            return view('shared.expired', compact('sharedReport'));
        }

        return view('shared.password', compact('sharedReport'));
    }

    /**
     * Verify password and show report
     */
    public function verify(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string'
        ], [
            'password.required' => 'Password harus diisi'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sharedReport = SharedReport::with(['blacklist', 'user'])
            ->where('token', $token)
            ->firstOrFail();

        if (!$sharedReport->isValid()) {
            return view('shared.expired', compact('sharedReport'));
        }

        if (!$sharedReport->verifyPassword($request->password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        // Mark as accessed
        $sharedReport->markAsAccessed();

        // Determine if data should be uncensored
        $showUncensored = $sharedReport->canAccessUncensoredData();

        return view('shared.report', compact('sharedReport', 'showUncensored'));
    }

    /**
     * Check if user can share a report
     */
    private function canShareReport($user, $blacklist)
    {
        // Admin can share any report
        if ($user->role === 'admin') {
            return true;
        }

        // Rental owner can share any report
        if ($user->role === 'pengusaha_rental') {
            return true;
        }

        // Regular user can only share if they have unlocked the data
        if ($user->role === 'pengguna') {
            return $user->hasUnlockedData($blacklist->id) ||
                   $user->hasUnlockedNik($blacklist->nik);
        }

        return false;
    }
}
