<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Keep the national holiday table fresh (catches newly-announced SKB dates) without
// any manual work. Weekly is plenty since holidays change at most yearly.
Schedule::command('holidays:sync')->weeklyOn(1, '03:00')->withoutOverlapping();

// Enforce the selfie retention window so attendance images don't fill the disk.
Schedule::command('attendance:prune-selfies')->dailyAt('02:30')->withoutOverlapping();
