<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Configuracion;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
    /*  public function boot(): void
    {
        $configuracion = Configuracion::first();
        View::share('configuracion', $configuracion);
    } */

    //bloque temporal para evitar error de migracion
    public function boot(): void
    {
        if (Schema::hasTable('configuracions')) {

            $configuracion = Configuracion::first();

            View::share(
                'configuracion',
                $configuracion
            );
        }
    }
}
