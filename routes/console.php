<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SubscriptionLifecycleCommand;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


app(Schedule::class)
    ->command(SubscriptionLifecycleCommand::class)
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();