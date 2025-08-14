<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard or login
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Auth routes (login, register, logout, etc.)
Auth::routes();

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware('auth')
->name('dashboard');

// Protected routes with permission middleware
Route::middleware(['auth'])->group(function () {
    // Reports Routes
    Route::middleware(['permission:reports.view'])->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ReportsController::class, 'show'])->name('reports.show');
    });

    Route::middleware(['permission:reports.manage'])->group(function () {
        Route::resource('reports', ReportsController::class)->except(['index', 'show']);
    });

    // Sites Routes
    Route::middleware(['permission:sites.view'])->group(function () {
        Route::get('/sites', [SitesController::class, 'index'])->name('sites.index');
        Route::get('/sites/{site}', [SitesController::class, 'show'])->name('sites.show');
    });

    Route::middleware(['permission:sites.manage'])->group(function () {
        Route::resource('sites', SitesController::class)->except(['index', 'show']);
    });

    // Clients Routes
    Route::middleware(['permission:clients.view'])->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    });

    Route::middleware(['permission:clients.manage'])->group(function () {
        Route::resource('clients', ClientController::class)->except(['index', 'show']);
    });

    // Admin Users Management
    Route::prefix('admin')->name('admin.')->middleware(['permission:users.manage'])->group(function () {
        // User CRUD
        Route::resource('users', UserController::class);
        
        // Additional user actions
        Route::post('users/{user}/change-password', [UserController::class, 'changePassword'])
            ->name('users.change-password');
            
        Route::get('users/{user}/permissions', [UserController::class, 'editPermissions'])
            ->name('users.permissions.edit')
            ->middleware('permission:permissions.assign');
            
        Route::post('users/{user}/permissions', [UserController::class, 'updatePermissions'])
            ->name('users.permissions.update')
            ->middleware('permission:permissions.assign');
    });
});

// Optional: HomeController if used by Auth::routes() default redirect
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');