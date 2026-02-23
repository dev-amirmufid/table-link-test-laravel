<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes - guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthWebController::class, 'webLogin'])->name('auth.login.post');
    
    Route::get('/register', [AuthWebController::class, 'register'])->name('auth.register');
    Route::post('/register', [AuthWebController::class, 'webRegister'])->name('auth.register.post');
});

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    // Logout
    Route::get('/logout', [AuthWebController::class, 'webLogout'])->name('auth.logout');
    Route::post('/logout', [AuthWebController::class, 'webLogout']);

    // Admin routes - role:admin only
    Route::middleware('web.role:admin')->group(function () {
        Route::get('/dashboard', [WebController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/dashboard/data', [WebController::class, 'dashboardData'])->name('admin.dashboard.data');
        Route::get('/users', [WebController::class, 'users'])->name('admin.users');
        Route::put('/users/{id}', [WebController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [WebController::class, 'deleteUser'])->name('admin.users.destroy');
        Route::get('/flights', [WebController::class, 'flights'])->name('admin.flights');
    });
    
    // User routes - role:user only
    Route::middleware('web.role:user')->group(function () {
        Route::get('/user/dashboard', [WebController::class, 'userDashboard'])->name('user.dashboard');
    });
});
