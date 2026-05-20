<?php

use Akshay\SchedulerListLaravel\Http\Controllers\SchedulerListController;
use Illuminate\Support\Facades\Route;

if (config('scheduler-list-laravel.enabled', true)) {
    Route::middleware(config('scheduler-list-laravel.middleware', ['web']))
        ->prefix(config('scheduler-list-laravel.path', 'schedulers'))
        ->group(function () {
            Route::get('/', [SchedulerListController::class, 'index'])->name('scheduler-list.index');
            Route::post('/run', [SchedulerListController::class, 'run'])->name('scheduler-list.run');
        });
}
