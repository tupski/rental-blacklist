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
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Public Access)
|--------------------------------------------------------------------------
*/

// Homepage and search
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/detail/{id}', [PublicController::class, 'detail'])->name('public.detail');

// Public information pages
Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsors.index');
Route::get('/sponsorship', [SponsorController::class, 'sponsorship'])->name('sponsors.sponsorship');
Route::get('/api-docs', [ApiController::class, 'documentation'])->name('api.docs');

// Guest reporting (no authentication required)
Route::get('/lapor', [ReportController::class, 'create'])->name('report.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('report.store');

// Rental pages
Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
Route::get('/daftar-rental', [RentalController::class, 'create'])->name('rental.register');
Route::post('/daftar-rental', [RentalController::class, 'store'])->name('rental.store');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Smart dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengusaha_rental') {
            return redirect()->route('rental.dashboard');
        } elseif ($user->role === 'user') {
            return redirect()->route('user.dashboard');
        }

        // Default fallback
        return redirect()->route('home');
    })->name('dashboard');

    // Data unlock (for regular users)
    Route::post('/unlock-data/{id}', [PublicController::class, 'unlockData'])->name('public.unlock');

    // Full detail access for unlocked data
    Route::get('/full-detail/{id}', [PublicController::class, 'fullDetail'])->name('public.full-detail');
    Route::get('/print-detail/{id}', [PublicController::class, 'printDetail'])->name('public.print-detail');
    Route::get('/download-pdf/{id}', [PublicController::class, 'downloadPDF'])->name('public.download-pdf');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Invoice routes
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
});

/*
|--------------------------------------------------------------------------
| Rental Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:pengusaha_rental'])->group(function () {

    // Dashboard
    Route::get('/rental/dashboard', [DashboardController::class, 'index'])->name('rental.dashboard');

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
});

/*
|--------------------------------------------------------------------------
| Regular User Routes (User Biasa)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {

    // Dashboard for regular users
    Route::get('/user/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/search', [App\Http\Controllers\UserDashboardController::class, 'search'])->name('user.search');
    Route::post('/user/unlock/{id}', [App\Http\Controllers\UserDashboardController::class, 'unlock'])->name('user.unlock');

    // Topup & Balance routes (only for regular users)
    Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
    Route::get('/topup/create', [TopupController::class, 'create'])->name('topup.create');
    Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');
    Route::get('/topup/confirm/{invoice}', [TopupController::class, 'confirm'])->name('topup.confirm');
    Route::post('/topup/upload-proof/{invoice}', [TopupController::class, 'uploadProof'])->name('topup.upload-proof');
    Route::get('/balance/history', [BalanceController::class, 'history'])->name('balance.history');
});

// Include authentication routes
require __DIR__.'/auth.php';
