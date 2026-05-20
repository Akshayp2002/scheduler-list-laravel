<?php

namespace Akshay\SchedulerListLaravel\Commands;

use Illuminate\Console\Command;

class SchedulerListLaravelCommand extends Command
{
    public $signature = 'scheduler-list-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
