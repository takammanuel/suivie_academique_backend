<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // 1. Métrique métier : Nombre de filières
        \Spatie\Prometheus\Facades\Prometheus::addGauge('nombre_de_filieres')
            ->value(function() {
                return \App\Models\Filiere::count();
            });

        // 2. Métrique système : RAM (Manuelle)
        // On calcule la mémoire réelle utilisée par le processus PHP
        \Spatie\Prometheus\Facades\Prometheus::addGauge('process_resident_memory_bytes')
            ->value(function() {
                return memory_get_usage(true);
            });
    }
}
