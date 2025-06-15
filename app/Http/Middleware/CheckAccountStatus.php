<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip check for guests
        if (!$user) {
            return $next($request);
        }

        // Skip check for admin
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if account is suspended
        if ($user->isSuspended()) {
            auth()->logout();
            return redirect()->route('masuk')->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator untuk informasi lebih lanjut.');
        }

        // For API routes, block if not active or email not verified
        if ($request->is('api/*') && !$user->canUseApi()) {
            $message = 'Akses ditolak.';
            if (!$user->isActive()) {
                $message = 'Akun belum aktif. Menunggu persetujuan admin.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.';
            }

            return response()->json([
                'error' => $message,
                'account_status' => $user->account_status,
                'email_verified' => $user->hasVerifiedEmail()
            ], 403);
        }

        return $next($request);
    }
}
