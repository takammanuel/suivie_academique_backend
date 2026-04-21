<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\UeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\EcController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\EnseigneController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\AuthController;

// --- ROUTES DE MONITORING (A placer en haut pour la priorité) ---
Route::get('/grafana-score', [MonitoringController::class, 'grafanaScore']);

// --- ROUTES PUBLIQUES ---
Route::post("/login", [AuthController::class, "login"]);

// --- ROUTES RESSOURCES ---
Route::apiResource('personnels', PersonnelController::class);
Route::apiResource('niveaux', NiveauController::class);
Route::apiResource('salles', SalleController::class);
Route::apiResource('filieres', FiliereController::class);
Route::apiResource('ecs', EcController::class);

Route::get('/filieres/export/excel', [FiliereController::class, 'exportExcel']);
Route::get('/filieres/export/pdf', [FiliereController::class, 'exportPdf']);

// --- ROUTES PROTEGEES (SANCTUM + VERIFICATION EXPIRATION TOKEN) ---
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function(){
    // Route de déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Routes ressources protégées
    Route::apiResource('ues', UeController::class);
    Route::apiResource('programmes', ProgrammeController::class);

    // Routes Enseigne
    Route::get('enseignes', [EnseigneController::class, 'index']);
    Route::post('enseignes', [EnseigneController::class, 'store']);
    Route::get('enseignes/{code_ec}/{code_personnel}', [EnseigneController::class, 'show']);
    Route::put('enseignes/{code_ec}/{code_personnel}', [EnseigneController::class, 'update']);
    Route::delete('enseignes/{code_ec}/{code_personnel}', [EnseigneController::class, 'destroy']);
});
