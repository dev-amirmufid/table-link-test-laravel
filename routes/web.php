<?php

use App\Http\Controllers\Web\Auth\WebAuthController;
use App\Http\Controllers\Web\Dashboard\UserDashboardController;
use App\Http\Controllers\Web\Dashboard\WebDashboardController;
use App\Http\Controllers\Web\Users\WebUserController;
use App\Http\Controllers\Web\Flights\WebFlightController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

// Protected routes (Session-based)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('web.role:admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [WebDashboardController::class, 'index'])->name('admin.dashboard');

        // User Management
        Route::get('/users', [WebUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [WebUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [WebUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}', [WebUserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [WebUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [WebUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [WebUserController::class, 'destroy'])->name('admin.users.destroy');

        // Flight Information
        Route::get('/flights', [WebFlightController::class, 'index'])->name('admin.flights.index');
        Route::get('/flights/create', [WebFlightController::class, 'create'])->name('admin.flights.create');
        Route::post('/flights', [WebFlightController::class, 'store'])->name('admin.flights.store');
        Route::get('/flights/{id}/edit', [WebFlightController::class, 'edit'])->name('admin.flights.edit');
        Route::put('/flights/{id}', [WebFlightController::class, 'update'])->name('admin.flights.update');
        Route::delete('/flights/{id}', [WebFlightController::class, 'destroy'])->name('admin.flights.destroy');
        Route::post('/flights/scrape', [WebFlightController::class, 'scrape'])->name('admin.flights.scrape');
    });
});
