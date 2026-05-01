<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
   ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'customer' => \App\Http\Middleware\CustomerMiddleware::class,
    ]);
    
    // DELETE THESE LINES:
    // $middleware->api(prepend: [
    //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    // ]);
})
    ->withSchedule(function (Schedule $schedule) {
        // Send abandoned cart reminders every hour
        $schedule->command('abandoned-cart:send-reminders')->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();