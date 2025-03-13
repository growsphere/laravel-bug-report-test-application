# Bug Report
## Project Setup
* Laravel 12.2.0
* sqlite 3.37.2
* php 8.2.27

This project is a fresh installation of laravel. Only the `bootstrap/app.php` has been modified to contain one scheduled event,
and the following classes - needed to demonstrate the issues - have been added.
* [SayHelloCommand.php](app/Console/Commands/SayHelloCommand.php)
* [ClassToBeInjected.php](app/BugReport/ClassToBeInjected.php)
* [ScheduleTest.php](tests/Feature/ScheduleTest.php)
* [SayHelloCommandTest.php](tests/Feature/SayHelloCommandTest.php)

## Issues
There seem to be substantial differences in the application when the context is a single phpunit test (or the first test
of many when ran together) and the subsequent tests. This is most likely due to the way the application is bootstrapped and/or
reset between tests.

## Issue 1: Schedule is not instantiated correctly after the first test.
There are two tests in the ScheduleTest.php file, but their content is identical.
All the tests do is get the Schedule from the app and assert that the count of the events is 1.
```
    $schedule = app(Schedule::class);
    $events = $schedule->events();
    $this->assertCount(1, $events);
```
In the bootstrap/app.php file, the Schedule is configured with one simple Hello World event.
```
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function () {
            logger()->info('Hello World');
        })->hourly();
    })
```
**Current test behavior:**
* When run in isolation, both tests pass. 
* When run together, the second test fails. 


## Issue 2: Calling an artisan command breaks mocking dependencies for tests.
This issue shows itself in more complex ways. The SayHelloCommandTest serves as a good example.
Here, again we have two identical tests. They call a simple command that uses dependency injection. 
```
    // SayHelloCommand constructor
    public function __construct(protected ClassToBeInjected $injected)
    {
        parent::__construct();
    }
```
This dependency is mocked in the tests.
As it is now, both tests succeed when run together or in isolation. But changing the following lines leads to issues:
* In `SayHelloCommandTest.php`, uncomment the line that calls `$this->artisan('inspire');` in the setUp() method.

    -> both tests fail (together or isolated), because they are not instantiated with the mock.
* In `SayHelloCommandTest.php`, uncomment the line that calls  `$this->seed()` the setUp() method.

  AND change `RefreshDatabase` to `LazilyRefreshDatabase` in the `tests/TestCase.php` Class.
    
    -> only the second test running fails, because only the second test is not instantiated with the mock.


## Expected behavior
* Application Classes like the Schedule should behave the same in all tests, regardless of the order they are run in.
* Calling artisan commands within tests should not break the subsequent instantiation of mocks.

## Further comments.
The second test in ScheduleTest.php **also fails** if the first test only asserts `assertTrue(true)`. The issue seems to be related to the 
fact that the second test is run after the first one. Something is not being reset correctly between tests somewhere in tearDown().
