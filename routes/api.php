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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route protégée par Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour la gestion des filières
Route::apiResource('filieres', FiliereController::class);
Route::apiResource('niveaux', NiveauController::class);
Route::apiResource('ues', UeController::class);
Route::apiResource('programmes', ProgrammeController::class);
Route::apiResource('ecs', EcController::class);
Route::apiResource('salles', SalleController::class);
Route::apiResource('personnels', PersonnelController::class);
Route::apiResource('enseignes', EnseigneController::class);
