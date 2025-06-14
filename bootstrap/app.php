<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'account.status' => \App\Http\Middleware\CheckAccountStatus::class,
        ]);

        // Apply account status check to all authenticated routes
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckAccountStatus::class);

        // Redirect unauthenticated users to 'masuk' route instead of 'login'
        $middleware->redirectGuestsTo(fn () => route('masuk'));

        // Redirect authenticated users from guest pages to dashboard
        $middleware->redirectUsersTo(fn () => route('dasbor'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
