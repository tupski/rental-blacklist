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
Route::get('/', [PublicController::class, 'index'])->name('beranda');
Route::post('/cari', [PublicController::class, 'search'])->name('publik.cari');
Route::get('/detail/{id}', [PublicController::class, 'detail'])->name('publik.detail');

// Public information pages
Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsor.indeks');
Route::get('/sponsorship', [SponsorController::class, 'sponsorship'])->name('sponsor.kemitraan');
Route::get('/dokumentasi-api', [ApiController::class, 'documentation'])->name('api.dokumentasi');

// Guest reporting (no authentication required)
Route::get('/lapor', [ReportController::class, 'create'])->name('laporan.buat');
Route::post('/lapor', [ReportController::class, 'store'])->name('laporan.simpan');

// Rental pages
Route::get('/rental', [RentalController::class, 'index'])->name('rental.indeks');
Route::get('/daftar-rental', [RentalController::class, 'create'])->name('rental.daftar');
Route::post('/daftar-rental', [RentalController::class, 'store'])->name('rental.simpan');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Smart dashboard redirect based on role
    Route::get('/dasbor', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dasbor');
        } elseif ($user->role === 'pengusaha_rental') {
            return redirect()->route('rental.dasbor');
        } elseif ($user->role === 'user') {
            return redirect()->route('pengguna.dasbor');
        }

        // Default fallback
        return redirect()->route('beranda');
    })->name('dasbor');

    // Data unlock (for regular users)
    Route::post('/buka-data/{id}', [PublicController::class, 'unlockData'])->name('publik.buka');

    // Full detail access for unlocked data
    Route::get('/detail-lengkap/{id}', [PublicController::class, 'fullDetail'])->name('publik.detail-lengkap');
    Route::get('/cetak-detail/{id}', [PublicController::class, 'printDetail'])->name('publik.cetak-detail');
    Route::get('/unduh-pdf/{id}', [PublicController::class, 'downloadPDF'])->name('publik.unduh-pdf');

    // Profile management
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.perbarui');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profil.hapus');

    // Blacklist management (accessible by all authenticated users)
    Route::prefix('dasbor/daftar-hitam')->name('dasbor.daftar-hitam.')->group(function () {
        Route::get('/', [BlacklistController::class, 'index'])->name('indeks');
        Route::get('/buat', [BlacklistController::class, 'create'])->name('buat');
        Route::post('/', [BlacklistController::class, 'store'])->name('simpan');
        Route::get('/{id}', [BlacklistController::class, 'show'])->name('tampil');
        Route::get('/{id}/edit', [BlacklistController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlacklistController::class, 'update'])->name('perbarui');
        Route::delete('/{id}', [BlacklistController::class, 'destroy'])->name('hapus');
        Route::post('/cari', [BlacklistController::class, 'searchForDashboard'])->name('cari');
    });

    // Invoice routes
    Route::get('/faktur/{id}', [InvoiceController::class, 'show'])->name('faktur.tampil');
    Route::get('/faktur/{id}/unduh', [InvoiceController::class, 'download'])->name('faktur.unduh');
});

/*
|--------------------------------------------------------------------------
| Rental Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:pengusaha_rental'])->group(function () {

    // Dashboard
    Route::get('/rental/dasbor', [DashboardController::class, 'index'])->name('rental.dasbor');

    // API Key management
    Route::prefix('kunci-api')->name('kunci-api.')->group(function () {
        Route::get('/', [App\Http\Controllers\ApiKeyController::class, 'show'])->name('tampil');
        Route::post('/buat', [App\Http\Controllers\ApiKeyController::class, 'generate'])->name('buat');
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
    Route::get('/pengguna/dasbor', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('pengguna.dasbor');
    Route::post('/pengguna/cari', [App\Http\Controllers\UserDashboardController::class, 'search'])->name('pengguna.cari');
    Route::post('/pengguna/buka/{id}', [App\Http\Controllers\UserDashboardController::class, 'unlock'])->name('pengguna.buka');

    // Topup & Balance routes (only for regular users)
    Route::get('/isi-saldo', [TopupController::class, 'index'])->name('isi-saldo.indeks');
    Route::get('/isi-saldo/buat', [TopupController::class, 'create'])->name('isi-saldo.buat');
    Route::post('/isi-saldo', [TopupController::class, 'store'])->name('isi-saldo.simpan');
    Route::get('/isi-saldo/konfirmasi/{invoice}', [TopupController::class, 'confirm'])->name('isi-saldo.konfirmasi');
    Route::post('/isi-saldo/unggah-bukti/{invoice}', [TopupController::class, 'uploadProof'])->name('isi-saldo.unggah-bukti');
    Route::get('/saldo/riwayat', [BalanceController::class, 'history'])->name('saldo.riwayat');
});

// Include authentication routes
require __DIR__.'/auth.php';
