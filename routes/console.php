<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Tasks untuk Monitoring Gemini Usage
Schedule::command('gemini:monitor-usage --days=1 --alert-threshold=100000')
    ->dailyAt('08:00')
    ->when(function () {
        // Hanya jalankan jika ada usage kemarin
        return \App\Models\ApiUsage::whereDate('created_at', Carbon::yesterday())->exists();
    })
    ->name('daily-gemini-monitor')
    ->description('Monitor daily Gemini API usage');

// Weekly summary setiap Senin
Schedule::command('gemini:monitor-usage --days=7')
    ->weeklyOn(1, '09:00')
    ->name('weekly-gemini-summary')
    ->description('Weekly Gemini API usage summary');

// Monthly summary setiap tanggal 1
Schedule::command('gemini:monitor-usage --days=30')
    ->monthlyOn(1, '10:00')
    ->name('monthly-gemini-summary')
    ->description('Monthly Gemini API usage summary');
