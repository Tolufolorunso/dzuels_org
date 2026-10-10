<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProgrammeController as AdminProgrammeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/programmes', [ProgrammeController::class, 'index'])->name('programmes.index');
Route::get('/programmes/{slug}', [ProgrammeController::class, 'show'])->name('programmes.show');

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

        // Team Management
        Route::patch('/team/{team}/toggle', [TeamMemberController::class, 'toggleActive'])->name('team.toggle');
        Route::resource('team', TeamMemberController::class)->except(['show']);

        // Partner Management
        Route::patch('/partners/{partner}/toggle', [PartnerController::class, 'toggleActive'])->name('partners.toggle');
        Route::resource('partners', PartnerController::class)->except(['show']);

        // Programme Management
        Route::patch('/programmes/{programme}/toggle', [AdminProgrammeController::class, 'toggleActive'])->name('programmes.toggle');
        Route::resource('programmes', AdminProgrammeController::class)->except(['show']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
