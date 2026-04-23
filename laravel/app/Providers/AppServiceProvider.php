<?php

namespace App\Providers;

use App\Events\Pagos\PagoRegistrado;
use App\Listeners\EnviarNotificacionNuevoPagoListener;
use App\Listeners\LogAuditoriaListener;
use App\Models\Pago;
use App\Models\ReporteMensual;
use App\Observers\PagoObserver;
use App\Observers\ReporteMensualObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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
        Paginator::useBootstrapFive();

        Pago::observe(PagoObserver::class);
        ReporteMensual::observe(ReporteMensualObserver::class);

        Event::listen(PagoRegistrado::class, [LogAuditoriaListener::class, 'handle']);
        Event::listen(PagoRegistrado::class, [EnviarNotificacionNuevoPagoListener::class, 'handle']);
    }
}
