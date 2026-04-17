<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * Expose les métriques pour Prometheus.
     */
    public function grafanaScore()
    {
        // On utilise un try-catch pour éviter que l'erreur DB ne bloque l'affichage
        try {
            // Test simple : on compte une table existante ou on met une valeur fixe
            $count = DB::table('filieres')->count();
        } catch (\Exception $e) {
            $count = 0;
        }

        $metrics = "academique_filiere_total " . $count . "\n";

        return response($metrics, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
