<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * A dónde redirigir a los usuarios después de hacer login.
     */
    public const HOME = '/home';

    public function boot(): void
    {
        // En Laravel 10/11 ya no hace falta nada más acá
    }
}

