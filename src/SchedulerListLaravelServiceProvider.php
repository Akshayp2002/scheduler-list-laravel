<?php

namespace Akshay\SchedulerListLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Akshay\SchedulerListLaravel\Commands\SchedulerListLaravelCommand;

class SchedulerListLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scheduler-list-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scheduler_list_laravel_table')
            ->hasCommand(SchedulerListLaravelCommand::class);
    }
}
