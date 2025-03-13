# Bug Report
## Project Setup
Laravel 12.2.0
sqlite 3.37.2
php 8.2.27

After installation, the only changes added were the following:
1. Added one scheduled event in the bootstrap/app.php file
2. Added tests/Feature/ScheduleTest.php
3. Removed the default test class tests/Feature/ExampleTest.php

## Current behavior
There are two tests in the ScheduleTest.php file, but the content is identical. When run in isolation, both tests pass. 
When run together, the second test fails. 

All the tests do is get the Schedule from the app and assert that the count of the events is 1.

## Expected behavior
Both tests should pass when run together.

## Further comments.
The second test **also fails** if the first test only asserts `assertTrue(true)`. The issue seems to be related to the 
fact that the second test is run after the first one. Something is not being reset correctly between tests somewhere in tearDown().
