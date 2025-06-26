<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BlacklistController as AdminBlacklistController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GuestReportController as AdminGuestReportController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ApplicationSettingController as AdminApplicationSettingController;
use App\Http\Controllers\Admin\SystemSettingController as AdminSystemSettingController;
use App\Http\Controllers\Admin\EmailSettingController as AdminEmailSettingController;
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
    Route::resource('daftar-hitam', AdminBlacklistController::class)->parameters([
        'daftar-hitam' => 'blacklist'
    ])->names([
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
    Route::resource('pengguna', AdminUserController::class)->parameters([
        'pengguna' => 'user'
    ])->names([
        'index' => 'pengguna.indeks',
        'create' => 'pengguna.buat',
        'store' => 'pengguna.simpan',
        'show' => 'pengguna.tampil',
        'edit' => 'pengguna.edit',
        'update' => 'pengguna.perbarui',
        'destroy' => 'pengguna.hapus'
    ]);

    // Account Approval
    Route::get('persetujuan-akun', [App\Http\Controllers\Admin\AccountApprovalController::class, 'index'])->name('persetujuan-akun.indeks');
    Route::post('account-approval/{user}/approve', [App\Http\Controllers\Admin\AccountApprovalController::class, 'approve'])->name('persetujuan-akun.setujui');
    Route::post('account-approval/{user}/reject', [App\Http\Controllers\Admin\AccountApprovalController::class, 'reject'])->name('persetujuan-akun.tolak');
    Route::post('account-approval/{user}/suspend', [App\Http\Controllers\Admin\AccountApprovalController::class, 'suspend'])->name('persetujuan-akun.nonaktifkan');
    Route::post('account-approval/{user}/activate', [App\Http\Controllers\Admin\AccountApprovalController::class, 'activate'])->name('persetujuan-akun.aktifkan');
    Route::post('pengguna/cek-email', [AdminUserController::class, 'checkEmail'])->name('pengguna.cek-email');
    Route::post('pengguna/{user}/ubah-status', [AdminUserController::class, 'toggleStatus'])->name('pengguna.ubah-status');
    Route::post('pengguna/{user}/reset-kata-sandi', [AdminUserController::class, 'resetPassword'])->name('pengguna.reset-kata-sandi');
    Route::post('pengguna/{user}/ban', [AdminUserController::class, 'ban'])->name('pengguna.ban');
    Route::post('pengguna/{user}/unban', [AdminUserController::class, 'unban'])->name('pengguna.unban');

    // Guest Reports Management
    Route::resource('laporan-tamu', AdminGuestReportController::class)->parameters([
        'laporan-tamu' => 'guestReport'
    ])->names([
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

    // Settings - Email
    Route::get('pengaturan/email', [AdminEmailSettingController::class, 'index'])->name('pengaturan.email.indeks');
    Route::put('pengaturan/email', [AdminEmailSettingController::class, 'update'])->name('pengaturan.email.perbarui');
    Route::post('pengaturan/email/tes', [AdminEmailSettingController::class, 'testSmtp'])->name('pengaturan.email.tes');

    // Settings - Payment
    Route::get('pengaturan/pembayaran', [AdminPaymentSettingController::class, 'index'])->name('pengaturan.pembayaran.indeks');
    Route::put('pengaturan/pembayaran', [AdminPaymentSettingController::class, 'update'])->name('pengaturan.pembayaran.perbarui');

    // Settings - Database
    Route::get('pengaturan/database', [AdminDatabaseSettingController::class, 'index'])->name('pengaturan.database.indeks');
    Route::post('pengaturan/database/bersihkan-cache', [AdminDatabaseSettingController::class, 'clearCache'])->name('pengaturan.database.bersihkan-cache');
    Route::post('pengaturan/database/optimasi', [AdminDatabaseSettingController::class, 'optimize'])->name('pengaturan.database.optimasi');
    Route::post('pengaturan/database/optimasi-db', [AdminDatabaseSettingController::class, 'optimizeDatabase'])->name('pengaturan.database.optimasi-db');
    Route::post('pengaturan/database/reset', [AdminDatabaseSettingController::class, 'resetDatabase'])->name('pengaturan.database.reset');
    Route::post('pengaturan/database/maintenance/aktifkan', [AdminDatabaseSettingController::class, 'enableMaintenance'])->name('pengaturan.database.maintenance.aktifkan');
    Route::post('pengaturan/database/maintenance/nonaktifkan', [AdminDatabaseSettingController::class, 'disableMaintenance'])->name('pengaturan.database.maintenance.nonaktifkan');

    // Reports & Analytics
    Route::get('laporan', [AdminReportController::class, 'index'])->name('laporan');
    Route::get('analitik', [AdminReportController::class, 'analytics'])->name('analitik');
    Route::get('laporan/data', [AdminReportController::class, 'getData'])->name('laporan.data');
    Route::get('laporan/ekspor', [AdminReportController::class, 'export'])->name('laporan.ekspor');

    // Notifications
    Route::get('notifikasi', [AdminNotificationController::class, 'getNotifications'])->name('notifikasi.ambil');
    Route::get('notifikasi/semua', [AdminNotificationController::class, 'index'])->name('notifikasi.indeks');
    Route::post('notifikasi/baca', [AdminNotificationController::class, 'markAsRead'])->name('notifikasi.baca');

    // System Maintenance
    Route::get('maintenance', [AdminDashboardController::class, 'maintenance'])->name('maintenance');
    Route::post('maintenance/clear-cache', [AdminDashboardController::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::post('maintenance/optimize', [AdminDashboardController::class, 'optimize'])->name('maintenance.optimize');

});
