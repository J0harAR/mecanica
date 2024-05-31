<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        //maquinaria que necesita mantenimiento
        $maquinaria_mantenimiento = DB::table('insumos_maquinaria')
        ->whereColumn('cantidad_actual', '=', 'cantidad_minima')
        ->get();

        $cantidad_maquinas=count($maquinaria_mantenimiento);


        $prestamos_pendientes=DB::table('prestamo')
        ->whereColumn('fecha_devolucion', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 1 DAY)'))
        ->where('estatus','Pendiente')
        ->get();
        $cantidad_prestamos=count($prestamos_pendientes);


        $total_notificaciones=$cantidad_maquinas + $cantidad_prestamos;

        View::composer('layouts.navbar', function ($view) use ($maquinaria_mantenimiento, $prestamos_pendientes,$total_notificaciones) {
            $view->with('maquinaria_mantenimiento', $maquinaria_mantenimiento)
                 ->with('prestamos_pendientes', $prestamos_pendientes)
                 ->with('total_notificaciones',$total_notificaciones);
        });




       Schema::defaultStringLength(191);
       Paginator::useBootstrap();
    }
}
