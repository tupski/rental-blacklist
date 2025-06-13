<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BlacklistController as AdminBlacklistController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GuestReportController as AdminGuestReportController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\TopupController as AdminTopupController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
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
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    // Blacklist Management
    Route::resource('blacklist', AdminBlacklistController::class);
    Route::post('blacklist/{blacklist}/validate', [AdminBlacklistController::class, 'validateBlacklist'])->name('blacklist.validate');
    Route::post('blacklist/{blacklist}/invalidate', [AdminBlacklistController::class, 'invalidateBlacklist'])->name('blacklist.invalidate');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/check-email', [AdminUserController::class, 'checkEmail'])->name('users.check-email');
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // Guest Reports Management
    Route::resource('guest-reports', AdminGuestReportController::class);
    Route::post('guest-reports/{guestReport}/approve', [AdminGuestReportController::class, 'approve'])->name('guest-reports.approve');
    Route::post('guest-reports/{guestReport}/reject', [AdminGuestReportController::class, 'reject'])->name('guest-reports.reject');

    // Sponsor Management
    Route::resource('sponsors', AdminSponsorController::class);
    Route::post('sponsors/{sponsor}/toggle-status', [AdminSponsorController::class, 'toggleStatus'])->name('sponsors.toggle-status');

    // Topup Management
    Route::get('topup', [AdminTopupController::class, 'index'])->name('topup.index');
    Route::get('topup/{topup}', [AdminTopupController::class, 'show'])->name('topup.show');
    Route::post('topup/{topup}/approve', [AdminTopupController::class, 'approve'])->name('topup.approve');
    Route::post('topup/{topup}/reject', [AdminTopupController::class, 'reject'])->name('topup.reject');
    Route::delete('topup/{topup}', [AdminTopupController::class, 'destroy'])->name('topup.destroy');

    // Topup Management
    Route::resource('topup', AdminTopupController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('topup/{topup}/confirm', [AdminTopupController::class, 'confirm'])->name('topup.confirm');
    Route::post('topup/{topup}/reject', [AdminTopupController::class, 'reject'])->name('topup.reject');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Reports & Analytics
    Route::get('reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');

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
