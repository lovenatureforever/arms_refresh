<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ProcessTaxReminders;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule tax reminder processing
Schedule::job(new ProcessTaxReminders())
    ->dailyAt('09:00')
    ->timezone('Asia/Kuala_Lumpur')
    ->name('process-tax-reminders')
    ->withoutOverlapping()
    ->onOneServer();

// Optional: Process tax reminders multiple times per day for urgent reminders
Schedule::job(new ProcessTaxReminders())
    ->dailyAt('14:00')
    ->timezone('Asia/Kuala_Lumpur')
    ->name('process-tax-reminders-afternoon')
    ->withoutOverlapping()
    ->onOneServer();
