<?php

// config for Akshay/SchedulerListLaravel
return [
    /*
     * The path/URL where the scheduler dashboard will be accessible.
     */
    'path' => 'schedulers',

    /*
     * The middleware applied to the scheduler dashboard routes.
     * You should restrict this in production (e.g. ['web', 'auth']).
     */
    'middleware' => ['web', 'auth'],

    /*
     * Whether the scheduler dashboard is enabled.
     */
    'enabled' => true,

    /*
     * Allow developers to run scheduled tasks manually from the dashboard.
     */
    'manual_execution' => true,
];
