<?php

namespace Akshay\SchedulerListLaravel;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
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

    public function packageBooted(): void
    {
        $ability = config('scheduler-list.ability', 'viewSchedulerList');

        if (! is_string($ability) || $ability === '' || Gate::has($ability)) {
            return;
        }

        Gate::define($ability, fn (?Authenticatable $user = null): bool => app()->environment('local'));
    }
}
