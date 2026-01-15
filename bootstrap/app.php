<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\UserType;
use App\Http\Middleware\EarlyTenantIdentification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    // ->withRouting(
    //     web: __DIR__.'/../routes/web.php',
    //     api: __DIR__.'/../routes/api.php',
    //     commands: __DIR__.'/../routes/console.php',
    //     health: '/up',
    // )
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            $centralDomains = config('tenancy.central_domains', []);

            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }

            Route::middleware('web')->group(base_path('routes/tenant.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Early tenant identification - runs BEFORE session middleware
        // This is needed for database sessions in multi-tenant setup
        $middleware->prepend(EarlyTenantIdentification::class);

        $middleware->group('universal', []);
        // $middleware->append(IsAdmin::class); // for global use
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            'user.type' => UserType::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
