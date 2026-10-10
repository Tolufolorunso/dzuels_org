<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

/*
|--------------------------------------------------------------------------
| Administrative Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Authentication
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Protected Administration
    Route::middleware(['admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.settings'))->name('dashboard');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/sync', [SettingController::class, 'syncApi'])->name('settings.sync');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
