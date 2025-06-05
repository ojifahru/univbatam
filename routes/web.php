<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\RegistrationController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Privacy policy route
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
// Add this route outside the localization group so it's not affected by language changes
Route::get('/proxy-image/{imagePath}', [DepartmentController::class, 'proxyImage'])
    ->name('proxy.image')
    ->where('imagePath', '.*'); // Allow slashes in the image path
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            'localize',                  // menentukan locale dari URL
            'localeSessionRedirect',     // redirect jika belum ada session
            'localeCookieRedirect',      // simpan locale di cookie
            'localeViewPath'             // arahkan ke folder view sesuai bahasa (jika pakai)
        ]
    ],
    function () {
        // Home page route
        Route::get('/', [HomeController::class, 'homePage'])->name('home');

        // Profile route with translation
        Route::get(LaravelLocalization::transRoute('routes.profile'), [ProfileController::class, 'index'])->name('profile');

        // Facilities index route
        Route::get(LaravelLocalization::transRoute('routes.facilities'), [FacilityController::class, 'index'])->name('facilities');

        // Facility detail route - use facility instead of facilities for detail pages
        Route::get(LaravelLocalization::transRoute('routes.facility'), [FacilityController::class, 'show'])
            ->name('facility.detail');

        // Route for Department
        Route::get(LaravelLocalization::transRoute('routes.department'), [DepartmentController::class, 'show'])
            ->name('department');

        // News routes - listing page
        Route::get(LaravelLocalization::transRoute('routes.news-prefix'), [NewsController::class, 'news'])->name('news');

        // News detail page with translation
        Route::get(LaravelLocalization::transRoute('routes.news-detail'), [NewsController::class, 'detail'])
            ->name('news.detail')
            ->where('slug', '[a-z0-9-]+');

        // Announcements listing page
        Route::get(LaravelLocalization::transRoute('routes.announcements-prefix'), [NewsController::class, 'announcement'])
            ->name('announcements');

        // Announcement detail page with translation
        Route::get(LaravelLocalization::transRoute('routes.announcement-detail'), [NewsController::class, 'detail'])
            ->name('announcements.detail')
            ->where('slug', '[a-z0-9-]+');

        // Identities route
        Route::get(LaravelLocalization::transRoute('routes.identities'), [IdentityController::class, 'index'])
            ->name('identities');

        // Registration information route
        Route::get(LaravelLocalization::transRoute('routes.registration-information'), [RegistrationController::class, 'information'])
            ->name('registration.information');
        Route::get(LaravelLocalization::transRoute('routes.registration-procedure'), [RegistrationController::class, 'procedure'])
            ->name('registration.procedure');
    }
);
