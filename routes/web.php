<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\RenderTextFormat;

// 1. Page d'accueil (C'est ici que tu modifieras le texte/couleur pour la démo)
Route::get('/', function () {
    return view('welcome');
});

// 2. Route de monitoring pour Prometheus (Celle qui répare le "123")
Route::get('/prometheus', function () {
    $registry = new CollectorRegistry(new InMemory());

    // Compte réel des filières
    $registry->getOrRegisterGauge('app', 'nombre_de_filieres', 'Total filieres')
             ->set(\App\Models\Filiere::count());

    // RAM réelle
    $registry->getOrRegisterGauge('app', 'process_resident_memory_bytes', 'RAM PHP')
             ->set(memory_get_usage(true));

    // CPU réel
    $registry->getOrRegisterGauge('app', 'process_cpu_seconds_total', 'CPU PHP')
             ->set(getrusage()['ru_utime.tv_sec']);

    $renderer = new RenderTextFormat();
    return response($renderer->render($registry->getMetricFamilySamples()))
                ->header('Content-Type', RenderTextFormat::MIME_TYPE);
});

// 3. Tes routes existantes
Route::get('/test-mail', function() {
    $filiere = App\Models\Filiere::latest()->first() ?? App\Models\Filiere::factory()->create();
    try {
        Mail::to(config('mail.from.address'))->send(new App\Mail\FiliereCreated($filiere));
        return 'Mail envoyé à ' . config('mail.from.address');
    } catch (\Throwable $e) {
        return 'Erreur envoi mail: ' . $e->getMessage();
    }
});

// Ajoute ici tes autres routes de contrôleurs si nécessaire
