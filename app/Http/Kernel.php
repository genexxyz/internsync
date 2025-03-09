<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        // ... existing middleware aliases
        'supervisor.first.login' => \App\Http\Middleware\SupervisorFirstLoginMiddleware::class,
    ];
        /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ...existing middleware...
        'supervisor.first.login' => \App\Http\Middleware\SupervisorFirstLoginMiddleware::class,
    ];
}