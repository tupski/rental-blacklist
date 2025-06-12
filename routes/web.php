<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/detail/{id}', [PublicController::class, 'detail'])->name('public.detail');

// Guest report routes
Route::get('/lapor', [ReportController::class, 'create'])->name('report.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('report.store');

// Rental registration routes
Route::get('/daftar-rental', [RentalController::class, 'create'])->name('rental.register');
Route::post('/daftar-rental', [RentalController::class, 'store'])->name('rental.store');

// API documentation
Route::get('/api-docs', [ApiController::class, 'documentation'])->name('api.docs');

// Dashboard routes (authenticated)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Blacklist management
    Route::prefix('dashboard/blacklist')->name('dashboard.blacklist.')->group(function () {
        Route::get('/', [BlacklistController::class, 'index'])->name('index');
        Route::get('/create', [BlacklistController::class, 'create'])->name('create');
        Route::post('/', [BlacklistController::class, 'store'])->name('store');
        Route::get('/{id}', [BlacklistController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BlacklistController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlacklistController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlacklistController::class, 'destroy'])->name('destroy');
        Route::post('/search', [BlacklistController::class, 'searchForDashboard'])->name('search');
    });

    // Admin Settings
    Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
