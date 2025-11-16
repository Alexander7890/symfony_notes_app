<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: __DIR__.'/../routes/health.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => App\Http\Middleware\Authenticate::class,
            'guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => App\Http\Middleware\EnsureEmailIsVerified::class,
            'trim' => App\Http\Middleware\TrimStrings::class,
            'throttle' => Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);

        $middleware->append([
            App\Http\Middleware\TrustProxies::class,
            Illuminate\Http\Middleware\HandleCors::class,
            App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            App\Http\Middleware\TrimStrings::class,
            Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // You can register exception handling callbacks here.
    })
    ->create();
