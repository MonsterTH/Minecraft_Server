<?php

use Illuminate\Support\Facades\Schedule;
use App\Services\LogParser;

Schedule::call(function () {
    app(LogParser::class)->parse(
        storage_path('logs/latest.log')
    );
})->everyMinute()->name('parse-minecraft-logs');
