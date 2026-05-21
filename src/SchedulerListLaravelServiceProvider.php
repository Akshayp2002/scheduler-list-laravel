<?php

namespace Akshay\SchedulerListLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasConfigFile('scheduler-list')
            ->hasViews()
            ->hasRoutes('web');
    }
}
