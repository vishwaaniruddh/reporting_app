<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

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

// Reports Routes (protected by authentication)
Route::resource('reports', \App\Http\Controllers\ReportsController::class)->middleware('auth');
Route::resource('sites', \App\Http\Controllers\SitesController::class)->middleware('auth');

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Auth routes (login, register, logout, etc.)
Auth::routes();



// Optional: HomeController if used by Auth::routes() default redirect
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');

Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->middleware('auth')->name('clients.index');

Route::get('/clients/create', [App\Http\Controllers\ClientController::class, 'create'])->middleware('auth')->name('clients.create');

Route::post('/clients', [App\Http\Controllers\ClientController::class, 'store'])->middleware('auth')->name('clients.store');

Route::get('/clients/{client}', [App\Http\Controllers\ClientController::class, 'show'])->middleware('auth')->name('clients.show');

Route::get('/clients/{client}/edit', [App\Http\Controllers\ClientController::class, 'edit'])->middleware('auth')->name('clients.edit');

Route::put('/clients/{client}', [App\Http\Controllers\ClientController::class, 'update'])->middleware('auth')->name('clients.update');

Route::delete('/clients/{client}', [App\Http\Controllers\ClientController::class, 'destroy'])->middleware('auth')->name('clients.destroy');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});
Route::post('users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])
    ->name('admin.users.change-password');


// // Reports

// Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->middleware('auth')->name('reports.index');

// Route::get('/reports/create', [App\Http\Controllers\ReportController::class, 'create'])->middleware('auth')->name('reports.create');

// Route::post('/reports', [App\Http\Controllers\ReportController::class, 'store'])->middleware('auth')->name('reports.store');

// Route::get('/reports/{client}', [App\Http\Controllers\ReportController::class, 'show'])->middleware('auth')->name('reports.show');

// Route::get('/reports/{client}/edit', [App\Http\Controllers\ReportController::class, 'edit'])->middleware('auth')->name('reports.edit');

// Route::put('/reports/{client}', [App\Http\Controllers\ReportController::class, 'update'])->middleware('auth')->name('reports.update');

// Route::delete('/reports/{client}', [App\Http\Controllers\ReportController::class, 'destroy'])->middleware('auth')->name('reports.destroy');
