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

Route::middleware(['auth', 'admin'])->prefix('backoffice')->group(function () {
    Route::resource('questions', QuestionController::class);

    // Route untuk usage statistics
    Route::get('/usage-stats', [FormController::class, 'showUsageStats'])->name('backoffice.usage_stats');

    // Route untuk export usage ke CSV/Excel
    Route::get('/usage-export', [FormController::class, 'exportUsage'])->name('backoffice.usage_export');
});

//analisa awal
Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('form', [FormController::class, 'showForm'])->name('front.form');
    Route::post('form', [FormController::class, 'submitForm'])->name('front.form.submit');
    Route::get('result/{session}', [FormController::class, 'showResult'])->name('front.result');
});

// Step 2: Analisa SWOT
Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('swot/{session}', [FormController::class, 'showSwotForm'])->name('front.swot.form');
    Route::post('swot/{session}', [FormController::class, 'submitSwot'])->name('front.swot.submit');
});

//konten generator
Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('content-plan/{session}', [FormController::class, 'showContentPlanForm'])->name('front.content.form');
    Route::post('content-plan/{session}', [FormController::class, 'generateContentPlan'])->name('front.content.generate');
});

//shooting script
Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('shooting-script/{contentIdea}', [FormController::class, 'showShootingScriptForm'])->name('front.shooting.form');
    Route::post('shooting-script/{contentIdea}', [FormController::class, 'generateShootingScript'])->name('front.shooting.generate');
});


Route::middleware(['auth', 'admin'])->prefix('backoffice')->group(function () {
    Route::get('sessions', [\App\Http\Controllers\Backoffice\SessionController::class, 'index'])->name('backoffice.sessions');
    Route::get('sessions/{session}', [\App\Http\Controllers\Backoffice\SessionController::class, 'show'])->name('backoffice.sessions.show');
});


Route::middleware(['auth'])->prefix('frontoffice')->group(function () {
    Route::get('history', [FormController::class, 'history'])->name('front.history');
});


//Route::middleware(['auth'])->group(function () {
//    Route::get('/frontoffice', [FrontofficeController::class, 'index'])->name('frontoffice.dashboard');
//});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backoffice.dashboard');
});


require __DIR__.'/auth.php';
