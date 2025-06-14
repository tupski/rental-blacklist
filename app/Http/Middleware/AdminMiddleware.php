<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Untuk sementara, semua user yang login dianggap admin
        // Nanti bisa ditambahkan role system
        if (!auth()->check()) {
            return redirect()->route('masuk')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
