<?php

namespace Tests\Feature;

use App\BugReport\ClassToBeInjected;
use Tests\TestCase;

class SayHelloCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        /** Uncomment the next line and both tests break (because they are not instantiated with the mock) */
//        $this->artisan('inspire');

        /** Uncomment the next line AND change RefreshDatabase to LazilyRefreshDatabase in the TestCase.php, and only the second running test breaks */
//        $this->seed();
    }

    public function test_command_uses_injected_class(): void
    {
        $this->mock(ClassToBeInjected::class)
            ->shouldReceive('sayHello')
            ->andReturn('Hi from Mock!');
        $this->artisan('app:say-hello')
            ->expectsOutput('Hi from Mock!')
            ->assertExitCode(0);
    }

    public function test_command_uses_injected_class_again(): void
    {
        $this->mock(ClassToBeInjected::class)
            ->shouldReceive('sayHello')
            ->andReturn('Hi from Mock!');
        $this->artisan('app:say-hello')
            ->expectsOutput('Hi from Mock!')
            ->assertExitCode(0);
    }
}
