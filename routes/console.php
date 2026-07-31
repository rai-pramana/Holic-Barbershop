<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queues:expire-pending')->everyMinute();
Schedule::command('queues:auto-skip')->everyMinute();
