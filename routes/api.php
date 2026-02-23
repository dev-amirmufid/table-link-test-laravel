<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\DashboardAPIController;
use App\Http\Controllers\API\FlightAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Build something great!
|
*/

// Authentication routes
Route::post('/auth/register', [AuthAPIController::class, 'register']);
Route::post('/auth/login', [AuthAPIController::class, 'login']);

// Protected routes
Route::middleware('api.auth')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthAPIController::class, 'logout']);
    Route::get('/auth/me', [AuthAPIController::class, 'me']);

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::get('/users', [UserAPIController::class, 'index'])->name('api.admin.users.index');
        Route::post('/users', [UserAPIController::class, 'store'])->name('api.admin.users.store');
        Route::get('/users/{id}', [UserAPIController::class, 'show'])->name('api.admin.users.show');
        Route::put('/users/{id}', [UserAPIController::class, 'update'])->name('api.admin.users.update');
        Route::delete('/users/{id}', [UserAPIController::class, 'destroy'])->name('api.admin.users.destroy');

        // Dashboard
        Route::get('/dashboard', [DashboardAPIController::class, 'index'])->name('api.admin.dashboard');
        Route::get('/dashboard/users-chart', [DashboardAPIController::class, 'usersChart']);
        Route::get('/dashboard/roles-chart', [DashboardAPIController::class, 'rolesChart']);
        Route::get('/dashboard/activity-chart', [DashboardAPIController::class, 'activityChart']);

        // Flight information
        Route::get('/flights', [FlightAPIController::class, 'index'])->name('api.admin.flights');
    });

    // User dashboard
    Route::get('/user/dashboard', [DashboardAPIController::class, 'index'])->name('api.user.dashboard');
});
