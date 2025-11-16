<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
    ])
    ->withMiddleware(function (Illuminate\Foundation\Application $app) {
        $app->routeMiddleware([
            'auth' => App\Http\Middleware\Authenticate::class,
            'guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $app->alias(
            Illuminate\Contracts\Console\Kernel::class,
            App\Console\Kernel::class
        );
    })
    ->create();
