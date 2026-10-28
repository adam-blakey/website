<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-contact-confirmation-emails')->everyMinute();
Schedule::command('music:fetch')->everyMinute();
Schedule::command('programming-time:fetch')->everyMinute();

Schedule::command('blog:import-wordpress')->hourly();
Schedule::command('blog:generate-responsive-images')->hourly();
Schedule::command('concerts:import')->hourly();

Schedule::command('music:clear-old')->daily();
