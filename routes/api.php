<?php

use App\Http\Controllers\Api\BlacklistApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes (no authentication required)
Route::prefix('v1')->middleware('throttle:100,1')->group(function () {
    // Search blacklist
    Route::get('/search', [BlacklistApiController::class, 'search']);
    Route::get('/blacklist/{id}', [BlacklistApiController::class, 'show']);

    // Statistics
    Route::get('/stats', [BlacklistApiController::class, 'stats']);
});

// Authenticated API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Blacklist detail for rental owners
    Route::get('/blacklist/{id}', function ($id) {
        $user = auth()->user();

        // Only allow rental owners to access this endpoint
        if ($user->role !== 'pengusaha_rental') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $blacklist = \App\Models\RentalBlacklist::with('user')->find($id);

        if (!$blacklist) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blacklist
        ]);
    });

    Route::prefix('v1')->group(function () {
        // Full access for authenticated users
        Route::get('/blacklist', [BlacklistApiController::class, 'index']);
        Route::post('/blacklist', [BlacklistApiController::class, 'store']);
        Route::put('/blacklist/{id}', [BlacklistApiController::class, 'update']);
        Route::delete('/blacklist/{id}', [BlacklistApiController::class, 'destroy']);
    });
});
