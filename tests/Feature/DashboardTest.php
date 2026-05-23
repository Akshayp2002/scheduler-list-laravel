<?php

use Illuminate\Console\Scheduling\Schedule;

beforeEach(function () {
    config(['scheduler-list.enabled' => true]);
    config(['scheduler-list.manual_execution' => true]);
    config(['scheduler-list.path' => 'schedulers']);
    config(['scheduler-list.middleware' => ['web']]);
    config(['scheduler-list.authorize' => fn () => true]);
});

it('can access the scheduler dashboard page', function () {
    $schedule = app(Schedule::class);
    $schedule->command('inspire')->hourly();

    $response = $this->get('/schedulers');

    $response->assertStatus(200);
    $response->assertSee('Scheduler Control Center');
    $response->assertSee('artisan inspire');
});

it('can manually execute a scheduled command via post', function () {
    $schedule = app(Schedule::class);
    $schedule->call(function () {
        echo 'Test executed successfully!';
    })->name('test-closure');

    $response = $this->postJson('/schedulers/run', [
        'id' => 0,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'output' => 'Test executed successfully!',
    ]);
});

it('can manually execute a scheduled artisan command via post', function () {
    $schedule = app(Schedule::class);
    $schedule->command('help')->hourly();

    $response = $this->postJson('/schedulers/run', [
        'id' => 0,
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('success', true);

    $output = $response->json('output');
    expect($output)->not->toBeEmpty();
    expect($output)->toContain('Usage');
});

it('blocks manual execution if configured to be disabled', function () {
    config(['scheduler-list.manual_execution' => false]);

    $schedule = app(Schedule::class);
    $schedule->command('inspire')->hourly();

    $response = $this->postJson('/schedulers/run', [
        'id' => 0,
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'output' => 'Manual execution is disabled in the configuration.',
    ]);
});

it('validates manual execution task id', function () {
    $response = $this->postJson('/schedulers/run', [
        'id' => 'not-a-task',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('id');
});

it('does not expose exception details when manual execution fails', function () {
    $schedule = app(Schedule::class);
    $schedule->call(function () {
        throw new RuntimeException('secret internal failure detail');
    })->name('failing-closure');

    $response = $this->postJson('/schedulers/run', [
        'id' => 0,
    ]);

    $response->assertStatus(500);
    $response->assertJson([
        'success' => false,
        'output' => 'Execution failed. Check the Laravel logs for details.',
    ]);
    $response->assertDontSee('secret internal failure detail');
});
