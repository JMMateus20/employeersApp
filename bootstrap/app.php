<?php

use App\Console\Commands\EnviarCorreoCumpleanieros;
use App\Console\Commands\EnviarCorreoEventos;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule){
        $schedule->command(EnviarCorreoCumpleanieros::class)->dailyAt('10:00');
        $schedule->command(EnviarCorreoEventos::class)->everyMinute();
    })
    ->create();
