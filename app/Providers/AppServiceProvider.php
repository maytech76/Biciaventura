<?php

namespace App\Providers;

use App\Models\Receivable;
use App\Models\OldPayment;
use App\Observers\ReceivableObserver;
use App\Observers\OldPaymentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Usar Bootstrap para paginación
        Paginator::useBootstrap();
        
        // Registrar Observers
        Receivable::observe(ReceivableObserver::class);
        OldPayment::observe(OldPaymentObserver::class);
    }
}