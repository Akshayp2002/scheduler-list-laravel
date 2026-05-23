<?php

namespace Akshay\SchedulerListLaravel\Tests;

use Akshay\SchedulerListLaravel\SchedulerListLaravelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SchedulerListLaravelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $compiledViewsPath = __DIR__.'/../build/test-views-'.str_replace('.', '', uniqid('', true));

        if (! is_dir($compiledViewsPath)) {
            mkdir($compiledViewsPath, 0777, true);
        }

        config()->set('database.default', 'testing');
        config()->set('view.compiled', $compiledViewsPath);
        config()->set('scheduler-list.enabled', true);
        config()->set('scheduler-list.middleware', ['web']);
        config()->set('scheduler-list.authorize', fn () => true);

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
