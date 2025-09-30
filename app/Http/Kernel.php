<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Ensure middleware alias is available on newer Laravel versions
    protected $middlewareAliases = [
        // ...existing aliases...
        'plan.limits' => \App\Http\Middleware\CheckPlanLimits::class,
    ];

    protected $routeMiddleware = [
        // ...existing middleware...
        'plan.limits' => \App\Http\Middleware\CheckPlanLimits::class,
    ];

    // ...existing code...
}