<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_schedule_has_event(): void
    {
        $schedule = app(Schedule::class);
        $events = $schedule->events();
        $this->assertCount(1, $events);
    }

    public function test_schedule_has_event_again(): void
    {
        $schedule = app(Schedule::class);
        $events = $schedule->events();
        $this->assertCount(1, $events);
    }
}
