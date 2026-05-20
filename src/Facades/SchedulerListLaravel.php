<?php

namespace Akshay\SchedulerListLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Akshay\SchedulerListLaravel\SchedulerListLaravel
 */
class SchedulerListLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Akshay\SchedulerListLaravel\SchedulerListLaravel::class;
    }
}
