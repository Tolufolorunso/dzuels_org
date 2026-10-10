<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\GalleryAlbumController as AdminGalleryAlbumController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProgrammeController as AdminProgrammeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GetInvolvedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
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
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/donate', DonateController::class)->name('donate');
Route::get('/get-involved', [GetInvolvedController::class, 'index'])->name('get-involved');
Route::post('/get-involved/volunteer', [GetInvolvedController::class, 'storeVolunteer'])->middleware('throttle:6,1')->name('get-involved.volunteer');
Route::post('/get-involved/partner', [GetInvolvedController::class, 'storePartner'])->middleware('throttle:6,1')->name('get-involved.partner');
Route::redirect('/partner', '/get-involved', 301);
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.submit');

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

        // News Management
        Route::patch('/news/{news}/toggle', [AdminPostController::class, 'togglePublish'])->name('news.toggle');
        Route::resource('news', AdminPostController::class)->except(['show']);

        // Inquiry Management (Volunteers & Partners)
        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::patch('/inquiries/volunteers/{volunteer}', [InquiryController::class, 'updateVolunteerStatus'])->name('inquiries.volunteers.status');
        Route::delete('/inquiries/volunteers/{volunteer}', [InquiryController::class, 'destroyVolunteer'])->name('inquiries.volunteers.destroy');
        Route::patch('/inquiries/partners/{partner}', [InquiryController::class, 'updatePartnerStatus'])->name('inquiries.partners.status');
        Route::delete('/inquiries/partners/{partner}', [InquiryController::class, 'destroyPartner'])->name('inquiries.partners.destroy');

        // Contact Messages Management
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::patch('/messages/{message}/status', [ContactMessageController::class, 'updateStatus'])->name('messages.status');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
