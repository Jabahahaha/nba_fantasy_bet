<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic game simulation
// Check every 5 minutes for games that have reached their start time
Schedule::command('games:simulate')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer();
