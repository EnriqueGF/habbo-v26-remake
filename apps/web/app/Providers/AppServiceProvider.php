<?php

namespace App\Providers;

use App\View\Composers\ChromeComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Datos del chrome (cabecera/nav/pie) para las páginas migradas. Se enlaza
        // a '*' para que estén disponibles también en las secciones de las vistas hijas.
        View::composer('*', ChromeComposer::class);
    }
}
