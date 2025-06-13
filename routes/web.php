<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\TopupController as AdminTopupController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/detail/{id}', [PublicController::class, 'detail'])->name('public.detail');

// Unlock data route (requires authentication)
Route::middleware('auth')->post('/unlock-data/{id}', [PublicController::class, 'unlockData'])->name('public.unlock');

// Sponsor routes
Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsors.index');
Route::get('/sponsorship', [SponsorController::class, 'sponsorship'])->name('sponsors.sponsorship');

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

    // API Key management
    Route::prefix('api-key')->name('api-key.')->group(function () {
        Route::get('/', [App\Http\Controllers\ApiKeyController::class, 'show'])->name('show');
        Route::post('/generate', [App\Http\Controllers\ApiKeyController::class, 'generate'])->name('generate');
        Route::post('/reset', [App\Http\Controllers\ApiKeyController::class, 'reset'])->name('reset');
    });

    // Admin Settings
    Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // Topup & Balance routes
    Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
    Route::get('/topup/create', [TopupController::class, 'create'])->name('topup.create');
    Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');
    Route::get('/topup/confirm/{invoice}', [TopupController::class, 'confirm'])->name('topup.confirm');
    Route::get('/balance/history', [BalanceController::class, 'history'])->name('balance.history');

    // Invoice routes
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');

    // Admin Sponsors
    Route::prefix('admin/sponsors')->name('admin.sponsors.')->group(function () {
        Route::get('/', [AdminSponsorController::class, 'index'])->name('index');
        Route::get('/create', [AdminSponsorController::class, 'create'])->name('create');
        Route::post('/', [AdminSponsorController::class, 'store'])->name('store');
        Route::get('/{sponsor}', [AdminSponsorController::class, 'show'])->name('show');
        Route::get('/{sponsor}/edit', [AdminSponsorController::class, 'edit'])->name('edit');
        Route::put('/{sponsor}', [AdminSponsorController::class, 'update'])->name('update');
        Route::delete('/{sponsor}', [AdminSponsorController::class, 'destroy'])->name('destroy');
    });

    // Admin Topup
    Route::prefix('admin/topup')->name('admin.topup.')->group(function () {
        Route::get('/', [AdminTopupController::class, 'index'])->name('index');
        Route::get('/{topup}', [AdminTopupController::class, 'show'])->name('show');
        Route::put('/{topup}/confirm', [AdminTopupController::class, 'confirm'])->name('confirm');
        Route::put('/{topup}/reject', [AdminTopupController::class, 'reject'])->name('reject');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
