<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\GalleryAlbumController as AdminGalleryAlbumController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProgrammeController as AdminProgrammeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
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
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{slug}/register', [EventController::class, 'register'])->name('events.register');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'album'])->name('gallery.album');

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

        // Event Management
        Route::patch('/events/{event}/toggle-active', [AdminEventController::class, 'toggleActive'])->name('events.toggle-active');
        Route::patch('/events/{event}/toggle-registration', [AdminEventController::class, 'toggleRegistration'])->name('events.toggle-registration');
        Route::get('/events/{event}/registrations', [AdminEventController::class, 'registrations'])->name('events.registrations');
        Route::delete('/events/{event}/registrations/{registration}', [AdminEventController::class, 'destroyRegistration'])->name('events.registrations.destroy');
        Route::resource('events', AdminEventController::class)->except(['show']);

        // Gallery Management
        Route::patch('/gallery/{album}/toggle', [AdminGalleryAlbumController::class, 'toggleActive'])->name('gallery.toggle');
        Route::get('/gallery/{album}/photos', [AdminGalleryAlbumController::class, 'photos'])->name('gallery.photos');
        Route::post('/gallery/{album}/photos', [AdminGalleryAlbumController::class, 'uploadPhotos'])->name('gallery.photos.upload');
        Route::delete('/gallery/{album}/photos/{photo}', [AdminGalleryAlbumController::class, 'destroyPhoto'])->name('gallery.photos.destroy');
        Route::resource('gallery', AdminGalleryAlbumController::class)->parameters(['gallery' => 'album'])->except(['show']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
