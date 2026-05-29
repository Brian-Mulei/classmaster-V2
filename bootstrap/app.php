<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');

        // Re-initialise tenancy for Livewire AJAX requests in path mode.
        // Livewire POSTs to /livewire/update which has no {tenant} prefix,
        // so InitializeTenancyByPath never runs. We detect the tenant from
        // the Referer header instead.
        $middleware->web(append: [
            \App\Http\Middleware\InitializeTenancyForLivewire::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
