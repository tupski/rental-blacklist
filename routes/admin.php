<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BlacklistController as AdminBlacklistController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GuestReportController as AdminGuestReportController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\TopupController as AdminTopupController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ApplicationSettingController as AdminApplicationSettingController;
use App\Http\Controllers\Admin\SystemSettingController as AdminSystemSettingController;
use App\Http\Controllers\Admin\SmtpSettingController as AdminSmtpSettingController;
use App\Http\Controllers\Admin\PaymentSettingController as AdminPaymentSettingController;
use App\Http\Controllers\Admin\DatabaseSettingController as AdminDatabaseSettingController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group.
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dasbor');
    Route::get('/dasbor', [AdminDashboardController::class, 'index'])->name('dasbor.indeks');

    // Blacklist Management
    Route::resource('daftar-hitam', AdminBlacklistController::class)->names([
        'index' => 'daftar-hitam.indeks',
        'create' => 'daftar-hitam.buat',
        'store' => 'daftar-hitam.simpan',
        'show' => 'daftar-hitam.tampil',
        'edit' => 'daftar-hitam.edit',
        'update' => 'daftar-hitam.perbarui',
        'destroy' => 'daftar-hitam.hapus'
    ]);
    Route::post('daftar-hitam/{blacklist}/validasi', [AdminBlacklistController::class, 'validateBlacklist'])->name('daftar-hitam.validasi');
    Route::post('daftar-hitam/{blacklist}/batalkan', [AdminBlacklistController::class, 'invalidateBlacklist'])->name('daftar-hitam.batalkan');

    // User Management
    Route::resource('pengguna', AdminUserController::class)->names([
        'index' => 'pengguna.indeks',
        'create' => 'pengguna.buat',
        'store' => 'pengguna.simpan',
        'show' => 'pengguna.tampil',
        'edit' => 'pengguna.edit',
        'update' => 'pengguna.perbarui',
        'destroy' => 'pengguna.hapus'
    ]);
    Route::post('pengguna/cek-email', [AdminUserController::class, 'checkEmail'])->name('pengguna.cek-email');
    Route::post('pengguna/{user}/ubah-status', [AdminUserController::class, 'toggleStatus'])->name('pengguna.ubah-status');
    Route::post('pengguna/{user}/reset-kata-sandi', [AdminUserController::class, 'resetPassword'])->name('pengguna.reset-kata-sandi');

    // Guest Reports Management
    Route::resource('laporan-tamu', AdminGuestReportController::class)->names([
        'index' => 'laporan-tamu.indeks',
        'create' => 'laporan-tamu.buat',
        'store' => 'laporan-tamu.simpan',
        'show' => 'laporan-tamu.tampil',
        'edit' => 'laporan-tamu.edit',
        'update' => 'laporan-tamu.perbarui',
        'destroy' => 'laporan-tamu.hapus'
    ]);
    Route::post('laporan-tamu/{guestReport}/setujui', [AdminGuestReportController::class, 'approve'])->name('laporan-tamu.setujui');
    Route::post('laporan-tamu/{guestReport}/tolak', [AdminGuestReportController::class, 'reject'])->name('laporan-tamu.tolak');

    // Sponsor Management
    Route::resource('sponsor', AdminSponsorController::class)->names([
        'index' => 'sponsor.indeks',
        'create' => 'sponsor.buat',
        'store' => 'sponsor.simpan',
        'show' => 'sponsor.tampil',
        'edit' => 'sponsor.edit',
        'update' => 'sponsor.perbarui',
        'destroy' => 'sponsor.hapus'
    ]);
    Route::post('sponsor/{sponsor}/ubah-status', [AdminSponsorController::class, 'toggleStatus'])->name('sponsor.ubah-status');

    // Topup Management
    Route::get('isi-saldo', [AdminTopupController::class, 'index'])->name('isi-saldo.indeks');
    Route::get('isi-saldo/{topup}', [AdminTopupController::class, 'show'])->name('isi-saldo.tampil');
    Route::post('isi-saldo/{topup}/setujui', [AdminTopupController::class, 'approve'])->name('isi-saldo.setujui');
    Route::post('isi-saldo/{topup}/tolak', [AdminTopupController::class, 'reject'])->name('isi-saldo.tolak');
    Route::delete('isi-saldo/{topup}', [AdminTopupController::class, 'destroy'])->name('isi-saldo.hapus');

    // Settings - General (keep for backward compatibility)
    Route::get('pengaturan', [AdminSettingController::class, 'index'])->name('pengaturan.indeks');
    Route::put('pengaturan', [AdminSettingController::class, 'update'])->name('pengaturan.perbarui');
    Route::post('pengaturan/tes-smtp', [AdminSettingController::class, 'testSmtp'])->name('pengaturan.tes-smtp');

    // Settings - Application
    Route::get('pengaturan/aplikasi', [AdminApplicationSettingController::class, 'index'])->name('pengaturan.aplikasi.indeks');
    Route::put('pengaturan/aplikasi', [AdminApplicationSettingController::class, 'update'])->name('pengaturan.aplikasi.perbarui');

    // Settings - System
    Route::get('pengaturan/sistem', [AdminSystemSettingController::class, 'index'])->name('pengaturan.sistem.indeks');
    Route::put('pengaturan/sistem', [AdminSystemSettingController::class, 'update'])->name('pengaturan.sistem.perbarui');

    // Settings - SMTP
    Route::get('pengaturan/smtp', [AdminSmtpSettingController::class, 'index'])->name('pengaturan.smtp.indeks');
    Route::put('pengaturan/smtp', [AdminSmtpSettingController::class, 'update'])->name('pengaturan.smtp.perbarui');
    Route::post('pengaturan/smtp/tes', [AdminSmtpSettingController::class, 'test'])->name('pengaturan.smtp.tes');

    // Settings - Payment
    Route::get('pengaturan/pembayaran', [AdminPaymentSettingController::class, 'index'])->name('pengaturan.pembayaran.indeks');
    Route::put('pengaturan/pembayaran', [AdminPaymentSettingController::class, 'update'])->name('pengaturan.pembayaran.perbarui');

    // Settings - Database
    Route::get('pengaturan/database', [AdminDatabaseSettingController::class, 'index'])->name('pengaturan.database.indeks');
    Route::post('pengaturan/database/bersihkan-cache', [AdminDatabaseSettingController::class, 'clearCache'])->name('pengaturan.database.bersihkan-cache');
    Route::post('pengaturan/database/optimasi', [AdminDatabaseSettingController::class, 'optimize'])->name('pengaturan.database.optimasi');
    Route::post('pengaturan/database/optimasi-db', [AdminDatabaseSettingController::class, 'optimizeDatabase'])->name('pengaturan.database.optimasi-db');

    // Reports & Analytics (placeholder routes)
    Route::get('laporan', function() { return view('admin.reports.index'); })->name('laporan');
    Route::get('analitik', function() { return view('admin.analytics.index'); })->name('analitik');

    // Notifications
    Route::get('notifikasi', [AdminNotificationController::class, 'getNotifications'])->name('notifikasi.ambil');
    Route::get('notifikasi/semua', [AdminNotificationController::class, 'index'])->name('notifikasi.indeks');
    Route::post('notifikasi/baca', [AdminNotificationController::class, 'markAsRead'])->name('notifikasi.baca');

    // System Maintenance
    Route::get('maintenance', [AdminDashboardController::class, 'maintenance'])->name('maintenance');
    Route::post('maintenance/clear-cache', [AdminDashboardController::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::post('maintenance/optimize', [AdminDashboardController::class, 'optimize'])->name('maintenance.optimize');

});
