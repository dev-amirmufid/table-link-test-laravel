<?php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\Users\ApiUserController;
use App\Http\Controllers\Api\Dashboard\ApiDashboardController;
use App\Http\Controllers\Api\Flights\ApiFlightController;
use Illuminate\Support\Facades\Route;

// Public API routes (for mobile apps or external API clients)
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
});

// Protected API routes - Using web (session) guard for SPA
// IMPORTANT: Add 'web' middleware to enable session for API routes
Route::middleware(['web', 'auth:web'])->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/user', [ApiAuthController::class, 'user']);
        Route::post('/logout', [ApiAuthController::class, 'logout']);
    });

    // User Management (Admin only)
    Route::middleware('role:admin')->prefix('users')->group(function () {
        Route::get('/', [ApiUserController::class, 'index']);
        Route::post('/', [ApiUserController::class, 'store']);
        Route::get('/{id}', [ApiUserController::class, 'show']);
        Route::delete('/{id}', [ApiUserController::class, 'destroy']);
    });

    // Dashboard (Admin only)
    Route::middleware('role:admin')->prefix('dashboard')->group(function () {
        Route::get('/charts', [ApiDashboardController::class, 'charts']);
    });

    // Flight Information (Admin only)
    Route::middleware('role:admin')->prefix('flights')->group(function () {
        Route::get('/', [ApiFlightController::class, 'index']);
        Route::post('/', [ApiFlightController::class, 'store']);
        Route::post('/scrape', [ApiFlightController::class, 'scrape']);
        Route::get('/{id}', [ApiFlightController::class, 'show']);
        Route::delete('/{id}', [ApiFlightController::class, 'destroy']);
    });
});
