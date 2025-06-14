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
use App\Http\Controllers\Admin\ReportController as AdminReportController;

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

    // Reports & Analytics (placeholder routes)
    Route::get('laporan', function() { return view('admin.reports.index'); })->name('laporan');
    Route::get('analitik', function() { return view('admin.analytics.index'); })->name('analitik');

    // Settings Categories
    Route::get('pengaturan/aplikasi', [AdminSettingController::class, 'application'])->name('pengaturan.aplikasi.indeks');
    Route::get('pengaturan/sistem', [AdminSettingController::class, 'system'])->name('pengaturan.sistem.indeks');
    Route::get('pengaturan/smtp', [AdminSettingController::class, 'smtp'])->name('pengaturan.smtp.indeks');
    Route::get('pengaturan/pembayaran', [AdminSettingController::class, 'payment'])->name('pengaturan.pembayaran.indeks');
    Route::get('pengaturan/database', [AdminSettingController::class, 'database'])->name('pengaturan.database.indeks');
    Route::get('maintenance', [AdminSettingController::class, 'maintenance'])->name('maintenance');

    // Notifications
    Route::get('notifikasi', [AdminNotificationController::class, 'get'])->name('notifikasi.ambil');
    Route::post('notifikasi/baca', [AdminNotificationController::class, 'markRead'])->name('notifikasi.baca');

    // Settings - Application
    Route::get('settings/application', [AdminApplicationSettingController::class, 'index'])->name('settings.application.index');
    Route::put('settings/application', [AdminApplicationSettingController::class, 'update'])->name('settings.application.update');

    // Settings - System
    Route::get('settings/system', [AdminSystemSettingController::class, 'index'])->name('settings.system.index');
    Route::put('settings/system', [AdminSystemSettingController::class, 'update'])->name('settings.system.update');

    // Settings - SMTP
    Route::get('settings/smtp', [AdminSmtpSettingController::class, 'index'])->name('settings.smtp.index');
    Route::put('settings/smtp', [AdminSmtpSettingController::class, 'update'])->name('settings.smtp.update');
    Route::post('settings/smtp/test', [AdminSmtpSettingController::class, 'testSmtp'])->name('settings.smtp.test');

    // Settings - Payment
    Route::get('settings/payment', [AdminPaymentSettingController::class, 'index'])->name('settings.payment.index');
    Route::put('settings/payment', [AdminPaymentSettingController::class, 'update'])->name('settings.payment.update');

    // Settings - Database
    Route::get('settings/database', [AdminDatabaseSettingController::class, 'index'])->name('settings.database.index');
    Route::post('settings/database/clear-cache', [AdminDatabaseSettingController::class, 'clearCache'])->name('settings.database.clear-cache');
    Route::post('settings/database/optimize', [AdminDatabaseSettingController::class, 'optimize'])->name('settings.database.optimize');
    Route::post('settings/database/optimize-db', [AdminDatabaseSettingController::class, 'optimizeDatabase'])->name('settings.database.optimize-db');

    // System Maintenance
    Route::get('maintenance', [AdminDashboardController::class, 'maintenance'])->name('maintenance');
    Route::post('maintenance/clear-cache', [AdminDashboardController::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::post('maintenance/optimize', [AdminDashboardController::class, 'optimize'])->name('maintenance.optimize');

    // Notifications
    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/get', [AdminNotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('notifications/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Reports & Analytics
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports');
    Route::get('analytics', [AdminReportController::class, 'analytics'])->name('analytics');
    Route::post('reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');
    Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');

});
