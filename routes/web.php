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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicRentalController;
use App\Http\Controllers\RegionController;
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
Route::get('/share/{token}', [PublicController::class, 'viewSharedReport'])->name('publik.share');
Route::post('/share/{token}/verify', [PublicController::class, 'verifySharedReport'])->name('publik.share.verify');

// Public information pages
Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsor.indeks');
Route::get('/sponsorship', [SponsorController::class, 'sponsorship'])->name('sponsor.kemitraan');
Route::get('/dokumentasi-api', [ApiController::class, 'documentation'])->name('api.dokumentasi');

// Contact page
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->name('kontak.kirim');

// Document verification
Route::get('/verifikasi-dokumen', [App\Http\Controllers\DocumentVerificationController::class, 'index'])->name('verifikasi.index');
Route::post('/verifikasi-dokumen', [App\Http\Controllers\DocumentVerificationController::class, 'verify'])->name('verifikasi.verify');

// Public rental profile and report timeline
Route::get('/rental/{id}/profil', [PublicRentalController::class, 'profile'])->name('rental.profil');
Route::get('/laporan/{nik}/timeline', [PublicRentalController::class, 'reportTimeline'])->name('laporan.timeline');
Route::get('/laporan/{id}/detail', [PublicRentalController::class, 'reportDetail'])->name('laporan.detail');

// Public detail page (accessible to all)
Route::get('/detail-laporan/{id}', [PublicRentalController::class, 'reportDetail'])->name('detail.laporan');

// Shared report routes
Route::get('/berbagi/{token}', [App\Http\Controllers\SharedReportController::class, 'view'])->name('shared.view');
Route::post('/berbagi/{token}/verifikasi', [App\Http\Controllers\SharedReportController::class, 'verify'])->name('shared.verify');
Route::get('/berbagi/{token}/verifikasi', [App\Http\Controllers\SharedReportController::class, 'redirectToPasswordForm'])->name('shared.verify.get');
Route::get('/berbagi/{token}/laporan', [App\Http\Controllers\SharedReportController::class, 'showReport'])->name('shared.report');

// Guest reporting (no authentication required)
Route::get('/lapor', [ReportController::class, 'create'])->name('laporan.buat');
Route::post('/lapor', [ReportController::class, 'store'])->name('laporan.simpan');

// Rental pages
Route::get('/rental', [RentalController::class, 'index'])->name('rental.indeks');
Route::get('/daftar-rental', [RentalController::class, 'create'])->name('rental.daftar');
Route::post('/daftar-rental', [RentalController::class, 'store'])->name('rental.simpan');

// API Wilayah Indonesia (public)
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/provinsi', [RegionController::class, 'provinces'])->name('provinsi');
    Route::get('/kabupaten/{provinceId}', [RegionController::class, 'regencies'])->name('kabupaten');
    Route::get('/kecamatan/{regencyId}', [RegionController::class, 'districts'])->name('kecamatan');
    Route::get('/kelurahan/{districtId}', [RegionController::class, 'villages'])->name('kelurahan');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Smart dashboard redirect based on role
    Route::get('/dasbor', function () {
        $user = auth()->user();

        // Clear any intended URL to prevent redirect loops
        session()->forget('url.intended');

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
    Route::post('/profil/verify-password', [ProfileController::class, 'verifyPassword'])->name('profil.verify-password');

    // Blacklist management (accessible by all authenticated users)
    Route::prefix('dasbor/daftar-hitam')->name('dasbor.daftar-hitam.')->group(function () {
        Route::get('/', [BlacklistController::class, 'index'])->name('indeks');
        // Redirect /buat ke /lapor
        Route::get('/buat', function () {
            return redirect()->route('laporan.buat');
        })->name('buat');
        Route::post('/', [BlacklistController::class, 'store'])->name('simpan');
        Route::get('/{id}', [BlacklistController::class, 'show'])->name('tampil');
        Route::get('/{id}/edit', [BlacklistController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlacklistController::class, 'update'])->name('perbarui');
        Route::delete('/{id}', [BlacklistController::class, 'destroy'])->name('hapus');
        Route::post('/cari', [BlacklistController::class, 'searchForDashboard'])->name('cari');
        Route::get('/{id}/pdf', [BlacklistController::class, 'generatePDF'])->name('pdf');
        Route::post('/{id}/bagikan', [App\Http\Controllers\SharedReportController::class, 'create'])->name('bagikan');
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
    Route::post('/rental/cari', [DashboardController::class, 'search'])->name('rental.cari');

    // Blacklist detail for rental owners
    Route::get('/rental/blacklist/{id}', function ($id) {
        $blacklist = \App\Models\RentalBlacklist::with('user')->find($id);

        if (!$blacklist) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blacklist
        ]);
    })->name('rental.blacklist.detail');

    // Print and PDF for rental owners
    Route::get('/rental/cetak-detail/{id}', [DashboardController::class, 'printDetail'])->name('rental.cetak-detail');
    Route::get('/rental/unduh-pdf/{id}', [DashboardController::class, 'downloadPDF'])->name('rental.unduh-pdf');
    Route::post('/rental/bagikan/{id}', [App\Http\Controllers\SharedReportController::class, 'create'])->name('rental.bagikan');

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
    Route::post('/pengguna/bagikan/{id}', [App\Http\Controllers\SharedReportController::class, 'create'])->name('pengguna.bagikan');

    // Topup & Balance routes (only for regular users)
    Route::get('/isi-saldo', [TopupController::class, 'index'])->name('isi-saldo.indeks');
    Route::get('/isi-saldo/buat', [TopupController::class, 'create'])->name('isi-saldo.buat');
    Route::post('/isi-saldo', [TopupController::class, 'store'])->name('isi-saldo.simpan');
    Route::get('/isi-saldo/konfirmasi/{invoice}', [TopupController::class, 'confirm'])->name('isi-saldo.konfirmasi');
    Route::post('/isi-saldo/unggah-bukti/{invoice}', [TopupController::class, 'uploadProof'])->name('isi-saldo.unggah-bukti');
    Route::get('/saldo/riwayat', [BalanceController::class, 'history'])->name('saldo.riwayat');
    Route::get('/saldo/riwayat/export-pdf', [BalanceController::class, 'exportPDF'])->name('saldo.export-pdf');
});

// Include authentication routes
require __DIR__.'/auth.php';
