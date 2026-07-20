<?php

namespace App\Providers;

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
        \App\Models\Client::observe(\App\Observers\ClientObserver::class);
        \App\Models\ProduitPhysique::observe(\App\Observers\ProduitPhysiqueObserver::class);
        \App\Models\BonCommande::observe(\App\Observers\BonCommandeObserver::class);
        \App\Models\BonCommandeLigne::observe(\App\Observers\BonCommandeLigneObserver::class);
        \App\Models\Facture::observe(\App\Observers\FactureObserver::class);
        \App\Models\FactureLigne::observe(\App\Observers\FactureLigneObserver::class);
        \App\Models\Avoir::observe(\App\Observers\AvoirObserver::class);
        \App\Models\AvoirLigne::observe(\App\Observers\AvoirLigneObserver::class);
    }
}
