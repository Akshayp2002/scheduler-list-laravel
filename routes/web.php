<?php

use Akshay\SchedulerListLaravel\Http\Controllers\SchedulerListController;
use Illuminate\Support\Facades\Route;

if (config('scheduler-list.enabled', true)) {
    Route::middleware(config('scheduler-list.middleware', ['web']))
        ->prefix(config('scheduler-list.path', 'schedulers'))
        ->group(function () {
            Route::get('/', [SchedulerListController::class, 'index'])->name('scheduler-list.index');
            Route::post('/run', [SchedulerListController::class, 'run'])->name('scheduler-list.run');
        });
}
