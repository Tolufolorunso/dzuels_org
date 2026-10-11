<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\GalleryAlbumController as AdminGalleryAlbumController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\NewsletterSubscriberController as AdminNewsletterSubscriberController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProgrammeController as AdminProgrammeController;
use App\Http\Controllers\Admin\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GetInvolvedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
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
Route::get('/privacy-policy', PrivacyPolicyController::class)->name('privacy-policy');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:6,1')->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'showUnsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe.confirm');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/site.webmanifest', function () {
    return response(file_get_contents(public_path('site.webmanifest')), 200, [
        'Content-Type' => 'application/manifest+json',
    ]);
})->name('webmanifest');

/*
|--------------------------------------------------------------------------
| Administrative Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Authentication & Password Recovery
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AdminResetPasswordController::class, 'reset'])->name('password.update');

    // Protected Administration
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Staff Profile & Password Security
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.password');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');

        // CMS Page Content Management
        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}/sections/{section}', [AdminPageController::class, 'updateSection'])->name('pages.sections.update');

        // Super Admin Restricted Routes (Settings & Users)
        Route::middleware(['super_admin'])->group(function () {
            Route::get('/settings', [SettingController::class, 'index'])->name('settings');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::post('/settings/sync', [SettingController::class, 'syncApi'])->name('settings.sync');

            // Staff User Management
            Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
            Route::resource('users', AdminUserController::class)->except(['show']);
        });

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

        // Newsletter Subscribers Management
        Route::get('/subscribers/export', [AdminNewsletterSubscriberController::class, 'exportCsv'])->name('subscribers.export');
        Route::get('/subscribers', [AdminNewsletterSubscriberController::class, 'index'])->name('subscribers.index');
        Route::delete('/subscribers/{subscriber}', [AdminNewsletterSubscriberController::class, 'destroy'])->name('subscribers.destroy');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
