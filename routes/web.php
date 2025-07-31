<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backoffice\QuestionController;
use App\Http\Controllers\Frontoffice\FormController;

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
    //analisa awal
    Route::get('form', [FormController::class, 'showForm'])->name('front.form');
    Route::post('form', [FormController::class, 'submitForm'])->name('front.form.submit');
    Route::get('result/{session}', [FormController::class, 'showResult'])->name('front.result');

    // Step 2: Analisa SWOT
    Route::get('swot/{session}', [FormController::class, 'showSwotForm'])->name('front.swot.form');
    Route::post('swot/{session}', [FormController::class, 'submitSwot'])->name('front.swot.submit');

    // Content plan
    Route::get('content-plan/{session}', [FormController::class, 'showContentPlanForm'])->name('front.content.form');
    Route::post('content-plan/{session}', [FormController::class, 'generateContentPlan'])->name('front.content.generate');

    //shooting script
    Route::get('shooting-script/{contentIdea}', [FormController::class, 'showShootingScriptForm'])->name('front.shooting.form');
    Route::post('shooting-script/{contentIdea}', [FormController::class, 'generateShootingScript'])->name('front.shooting.generate');

    // Generate iklan
        // Form
    Route::get('/session/{session}/ads', [FormController::class, 'showAdsForm'])->name('front.ads.form');
        // Proses Generate
    Route::post('/session/{session}/ads/generate', [FormController::class, 'generateAds'])->name('front.ads.generate');

    // History analisa
    Route::get('history', [FormController::class, 'history'])->name('front.history');
});


//Route::middleware(['auth'])->group(function () {
//    Route::get('/frontoffice', [FrontofficeController::class, 'index'])->name('frontoffice.dashboard');
//});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backoffice.dashboard');
});


require __DIR__.'/auth.php';
