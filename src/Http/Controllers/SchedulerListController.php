<?php

namespace Akshay\SchedulerListLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use Carbon\Carbon;

class SchedulerListController extends Controller
{
    public function index()
    {
        $schedule = app(Schedule::class);
        $kernel = app(ConsoleKernelContract::class);

        // 1. Force loading schedules from the Console Kernel.
        if (method_exists($kernel, 'resolveConsoleSchedule')) {
            $kernel->resolveConsoleSchedule();
        } elseif (method_exists($kernel, 'schedule')) {
            try {
                $reflection = new ReflectionClass($kernel);
                $method = $reflection->getMethod('schedule');
                $method->setAccessible(true);
                $method->invoke($kernel, $schedule);
            } catch (\Throwable $e) {
                // Fail-safe
            }
        }

        // 2. Load console routes/console.php if it exists in the host application
        if (file_exists(base_path('routes/console.php'))) {
            try {
                (static function () use ($schedule) {
                    $app = app();
                    include_once base_path('routes/console.php');
                })();
            } catch (\Throwable $e) {
                // Fail-safe
            }
        }

        // 3. Process each event
        $events = collect($schedule->events())->map(function ($event, $index) {
            $command = $event->command ?? '';
            $summary = method_exists($event, 'getSummaryForDisplay') ? $event->getSummaryForDisplay() : '';
            
            // Clean up path and binary prefixes for aesthetic display
            $cleanCommand = $command;
            if (empty($cleanCommand) && !empty($summary)) {
                $cleanCommand = $summary;
            } else {
                // If it is an artisan command, extract it cleanly
                if (preg_match('/(?:\b|["\'])artisan(?:\b|["\'])\s+(.*)$/i', $cleanCommand, $matches)) {
                    $cleanCommand = 'php artisan ' . trim($matches[1], '"\' ');
                } else {
                    // Strip PHP binary path at the beginning for other types of scripts
                    $cleanCommand = preg_replace('/^["\']?[^"\']+\bphp(?:\.exe)?["\']?\s+/', 'php ', $cleanCommand);
                    $cleanCommand = trim($cleanCommand, "'\" ");
                }
            }

            // Determine task type
            $type = 'Callback';
            if ($event instanceof \Illuminate\Console\Scheduling\CallbackEvent) {
                $type = 'Callback';
            } elseif (str_contains($command, 'artisan')) {
                $type = 'Artisan';
            } elseif (!empty($command)) {
                $type = 'Shell';
            }

            // Try to calculate human-readable next run date & expression description
            $nextRun = 'N/A';
            $nextRunDiff = 'N/A';
            try {
                $nextRunDate = Carbon::parse($event->nextRunDate());
                $nextRun = $nextRunDate->toDateTimeString();
                $nextRunDiff = $nextRunDate->diffForHumans();
            } catch (\Throwable $e) {
                // Fail-safe
            }

            // Parse constraints
            $constraints = [];
            if (isset($event->withoutOverlapping) && $event->withoutOverlapping) {
                $constraints[] = 'Without Overlapping';
            }
            if (isset($event->onOneServer) && $event->onOneServer) {
                $constraints[] = 'On One Server';
            }
            if (isset($event->evenInMaintenanceMode) && $event->evenInMaintenanceMode) {
                $constraints[] = 'In Maintenance';
            }
            if (isset($event->runInBackground) && $event->runInBackground) {
                $constraints[] = 'Background';
            }
            if (!empty($event->environments)) {
                $constraints[] = 'Env: ' . implode(', ', $event->environments);
            }

            return [
                'id' => $index,
                'raw_command' => $command,
                'command' => $cleanCommand,
                'summary' => $summary ?: $cleanCommand,
                'expression' => $event->expression,
                'timezone' => $event->timezone ?? config('app.timezone'),
                'next_run' => $nextRun,
                'next_run_diff' => $nextRunDiff,
                'description' => $event->description ?: 'No description provided.',
                'type' => $type,
                'constraints' => $constraints,
            ];
        });

        return view('scheduler-list-laravel::dashboard', compact('events'));
    }

    public function run(Request $request)
    {
        if (!config('scheduler-list-laravel.manual_execution', true)) {
            return response()->json([
                'success' => false,
                'output' => 'Manual execution is disabled in the configuration.',
            ], 403);
        }

        $id = $request->input('id');
        $schedule = app(Schedule::class);
        $kernel = app(ConsoleKernelContract::class);

        // Load all schedules
        if (method_exists($kernel, 'resolveConsoleSchedule')) {
            $kernel->resolveConsoleSchedule();
        } elseif (method_exists($kernel, 'schedule')) {
            try {
                $reflection = new ReflectionClass($kernel);
                $method = $reflection->getMethod('schedule');
                $method->setAccessible(true);
                $method->invoke($kernel, $schedule);
            } catch (\Throwable $e) {
            }
        }

        if (file_exists(base_path('routes/console.php'))) {
            try {
                (static function () use ($schedule) {
                    $app = app();
                    include_once base_path('routes/console.php');
                })();
            } catch (\Throwable $e) {
            }
        }

        $events = $schedule->events();

        if (!isset($events[$id])) {
            return response()->json([
                'success' => false,
                'output' => 'Scheduled task not found.',
            ], 404);
        }

        $event = $events[$id];

        try {
            ob_start();
            
            $output = '';
            // If it is an Artisan command, let's execute it directly via the Artisan Facade to capture standard console output.
            if (isset($event->command) && str_contains($event->command, 'artisan')) {
                $parts = preg_split('/\s+/', $event->command);
                $artisanIndex = -1;
                foreach ($parts as $idx => $part) {
                    $cleanPart = trim($part, "'\" ");
                    if ($cleanPart === 'artisan') {
                        $artisanIndex = $idx;
                        break;
                    }
                }
                
                if ($artisanIndex !== -1 && isset($parts[$artisanIndex + 1])) {
                    $commandName = trim($parts[$artisanIndex + 1], "'\" ");
                    
                    // Reassemble arguments
                    $arguments = [];
                    for ($i = $artisanIndex + 2; $i < count($parts); $i++) {
                        $arg = trim($parts[$i], "'\" ");
                        if (empty($arg)) continue;
                        
                        if (str_starts_with($arg, '-')) {
                            if (str_contains($arg, '=')) {
                                [$key, $val] = explode('=', $arg, 2);
                                $arguments[$key] = trim($val, "'\" ");
                            } else {
                                $arguments[$arg] = true;
                            }
                        } else {
                            $arguments[] = $arg;
                        }
                    }
                    
                    ob_get_clean(); // Discard the active buffer started by ob_start()
                    Artisan::call($commandName, $arguments);
                    $output = Artisan::output();
                } else {
                    $event->run(app());
                    $output = ob_get_clean();
                }
            } else {
                // Otherwise (Callback / Shell / Composer events), run normally
                $event->run(app());
                $output = ob_get_clean();
            }
            
            if (empty($output)) {
                $output = "Task executed successfully (no output returned).";
            }

            return response()->json([
                'success' => true,
                'output' => trim($output),
            ]);
        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            Log::error('Manual schedule run failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'output' => 'Execution failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
