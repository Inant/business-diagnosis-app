<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backoffice\QuestionController;
use App\Http\Controllers\Frontoffice\FormController;
use App\Http\Controllers\Frontoffice\GeneratorController;
use App\Http\Controllers\Frontoffice\AdsController;
use App\Http\Controllers\Frontoffice\SocialMediaImageGeneratorController;
use App\Http\Controllers\Frontoffice\FotoProductController;
use App\Http\Controllers\Frontoffice\LandingPageController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//ADMIN
Route::middleware(['auth', 'admin'])->prefix('backoffice')->group(function () {
    // Question management
    Route::resource('questions', QuestionController::class);

    // User session
    Route::get('sessions', [\App\Http\Controllers\Backoffice\SessionController::class, 'index'])->name('backoffice.sessions');
    Route::get('sessions/{session}', [\App\Http\Controllers\Backoffice\SessionController::class, 'show'])->name('backoffice.sessions.show');

    // Usage statistics
    Route::get('/usage-stats', [FormController::class, 'showUsageStats'])->name('backoffice.usage_stats');

    // Export usage ke CSV/Excel
    Route::get('/usage-export', [FormController::class, 'exportUsage'])->name('backoffice.usage_export');
});

//USER
Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('/dashboard', [FormController::class, 'dashboard'])->name('front.dashboard');
    //analisa awal
    Route::get('form', [FormController::class, 'showForm'])->name('front.form');
    Route::post('form', [FormController::class, 'submitForm'])->name('front.form.submit');
    Route::get('result/{session}', [FormController::class, 'showResult'])->name('front.result');

    // Step 2: Analisa SWOT
    Route::get('swot/{session}', [FormController::class, 'showSwotForm'])->name('front.swot.form');
    Route::post('swot/{session}', [FormController::class, 'submitSwot'])->name('front.swot.submit');

    Route::get('/content-history', [FormController::class, 'contentHistory'])->name('front.content.history');
    Route::get('/content/create', [FormController::class, 'showContentPlanForm'])->name('front.content.create');
    Route::post('/content/generate', [FormController::class, 'generateContentPlan'])->name('front.content.generate');
    Route::get('/content/{contentPlanId}', [FormController::class, 'showContentDetail'])->name('front.content.detail');

    //shooting script
    Route::get('shooting-script/{contentIdea}', [FormController::class, 'showShootingScriptForm'])->name('front.shooting.form');
    Route::post('shooting-script/{contentIdea}', [FormController::class, 'generateShootingScript'])->name('front.shooting.generate');

    // Ads generation routes
    Route::get('/ads-history', [AdsController::class, 'adsHistory'])->name('front.ads.history');
    Route::get('/ads/create', [AdsController::class, 'showAdsForm'])->name('front.ads.create');
    Route::post('/ads/generate', [AdsController::class, 'generateAds'])->name('front.ads.generate');
    Route::get('/ads/{adsPlanId}', [AdsController::class, 'showAdsDetail'])->name('front.ads.detail');

    // Backward compatibility
    Route::get('/ads/{session_id}', function($session_id) {
        return redirect()->route('front.ads.create');
    })->name('front.ads.form');

    Route::post('/ads/{session_id}', function($session_id) {
        return redirect()->route('front.ads.generate');
    })->name('front.ads.store');

    Route::get('/generator/social-media-image-generator', [SocialMediaImageGeneratorController::class, 'index'])
        ->name('social-media-image-generator.index');

    Route::post('/generator/social-media-image-generator', [SocialMediaImageGeneratorController::class, 'generate'])
        ->name('social-media-image-generator.generate')->middleware('long.request');

    Route::get(
        '/generator/social-media-image-generator/download',
        [SocialMediaImageGeneratorController::class, 'download']
    )->name('social-media-image-generator.download');

    Route::get('/generator/foto-product', [FotoProductController::class, 'index'])
        ->name('foto-product.index');

    Route::post('/generator/foto-product', [FotoProductController::class, 'generate'])
        ->name('foto-product.generate')->middleware('long.request');

    Route::get('/generator/landing-page', [LandingPageController::class, 'index'])
        ->name('landing-page.index');


//    // Generate iklan
//        // Form
//    Route::get('/session/{session}/ads', [FormController::class, 'showAdsForm'])->name('front.ads.form');
//        // Proses Generate
//    Route::post('/session/{session}/ads/generate', [FormController::class, 'generateAds'])->name('front.ads.generate');
//
//    // History analisa
//    Route::get('history', [FormController::class, 'history'])->name('front.history');
});


//Route::middleware(['auth'])->group(function () {
//    Route::get('/frontoffice', [FrontofficeController::class, 'index'])->name('frontoffice.dashboard');
//});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backoffice.dashboard');
});

//Route::get('frontoffice/social-media-post', [GeneratorController::class, 'socialMediaPost'])->name('front.social-media-post');

//Route::get('frontoffice/veo3', [GeneratorController::class, 'veo3'])->name('front.veo3');




require __DIR__.'/auth.php';
