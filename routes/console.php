<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('expense:generate-recurring-expense')->everyFifteenSeconds()
    ->withoutOverlapping()
    ->onSuccess(function () {
        // Actions to perform on success
        Log::info('Successfully executed expense:generate-recurring-expense command.');
    })->onFailure(function () {
        // Actions to perform on failure
        Log::error('Failed to execute expense:generate-recurring-expense command.');
    });
