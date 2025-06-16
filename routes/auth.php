<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('daftar', [RegisteredUserController::class, 'create'])
        ->name('daftar');

    Route::post('daftar', [RegisteredUserController::class, 'store']);

    Route::get('masuk', [AuthenticatedSessionController::class, 'create'])
        ->name('masuk');

    // Alias untuk kompatibilitas dengan Laravel default
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('masuk', [AuthenticatedSessionController::class, 'store']);

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('lupa-kata-sandi', [PasswordResetLinkController::class, 'create'])
        ->name('kata-sandi.permintaan');

    Route::post('lupa-kata-sandi', [PasswordResetLinkController::class, 'store'])
        ->name('kata-sandi.email');

    Route::get('reset-kata-sandi/{token}', [NewPasswordController::class, 'create'])
        ->name('kata-sandi.reset');

    Route::post('reset-kata-sandi', [NewPasswordController::class, 'store'])
        ->name('kata-sandi.simpan');
});

Route::middleware('auth')->group(function () {
    Route::get('verifikasi-email', EmailVerificationPromptController::class)
        ->name('verifikasi.pemberitahuan');

    Route::get('verifikasi-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verifikasi.verifikasi');

    // Alias untuk kompatibilitas Laravel default
    Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/pemberitahuan-verifikasi', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verifikasi.kirim');

    Route::get('konfirmasi-kata-sandi', [ConfirmablePasswordController::class, 'show'])
        ->name('kata-sandi.konfirmasi');

    Route::post('konfirmasi-kata-sandi', [ConfirmablePasswordController::class, 'store']);

    Route::put('kata-sandi', [PasswordController::class, 'update'])->name('kata-sandi.perbarui');

    Route::post('keluar', [AuthenticatedSessionController::class, 'destroy'])
        ->name('keluar');
});
