<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // 👇 Load API routes (with the /api prefix)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // 👇 Load Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
