<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\DeletePhotos;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$schedule = app(Schedule::class);
$schedule->command(DeleteOldPhotos::class)->dailyAt('00:00');
// $schedule->command(DeleteOldPhotos::class)->everyFiveMinutes();