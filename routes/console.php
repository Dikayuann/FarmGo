<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule to send pending notifications every hour
Schedule::command('notifications:send-pending')->hourly();

// Schedule to send health checkup reminders daily at 8 AM
Schedule::command('health:send-reminders')->dailyAt('08:00');

// Schedule to send birth reminders daily at 8 AM
Schedule::command('birth:send-reminders')->dailyAt('08:00');
