<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'auth.user'     => \App\Http\Middleware\AuthenticateUser::class,
            'auth.employee' => \App\Http\Middleware\AuthenticateEmployee::class,
            'role'          => \App\Http\Middleware\CheckEmployeeRole::class,
            'auth.user_or_employee' => \App\Http\Middleware\AuthenticateUserOrEmployee::class,
        ]);
        $middleware->preventRequestForgery(except: [
            'webhook/paymongo',
        ]);
        
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
