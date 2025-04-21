<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TikTokController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TikTokController::class, 'index'])->name('home');

// User profile and video routes
Route::get('/search', [TikTokController::class, 'search'])->name('search');
Route::get('/user', [TikTokController::class, 'userProfile'])->name('user.lookup');
Route::get('/user/{username}', [TikTokController::class, 'userProfile'])->name('user.profile');
Route::get('/user/{username}/video/{videoId}', [TikTokController::class, 'userProfile'])->name('video.view');
Route::post('/load-more', [TikTokController::class, 'loadMorePosts'])->name('load.more');

// Informational pages for SEO
Route::get('/how-it-works', [TikTokController::class, 'howItWorks'])->name('how.it.works');
Route::get('/popular-tiktok-profiles', [TikTokController::class, 'popularProfiles'])->name('popular.profiles');
Route::get('/tiktok-tips', [TikTokController::class, 'tikTokTips'])->name('tiktok.tips');

// Legal pages
Route::get('/privacy-policy', function() {
    return view('pages.privacy-policy');
})->name('privacy.policy');

Route::get('/terms-of-use', function() {
    return view('pages.terms-of-use');
})->name('terms.of.use');

Route::get('/legal', function() {
    return view('pages.legal');
})->name('legal');

// Test route
Route::get('/test', function() {
    return view('test');
})->name('test');

// Admin routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/warm-cache', [TikTokController::class, 'warmTrendingCache'])->name('admin.warm.cache');
});

// Diagnostic routes
Route::prefix('diagnostic')->group(function () {
    Route::get('/cache-test', [TikTokController::class, 'testCache'])->name('diagnostic.cache.test');
}); 