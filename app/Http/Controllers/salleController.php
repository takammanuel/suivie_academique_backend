<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Affiche la liste des salles.
     */
    public function index()
    {
        $salles = Salle::all();
        return response()->json($salles, 200);
    }

    /**
     * Enregistre une nouvelle salle.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'contenance' => 'required|min:5|string',
                'status' => 'required|min:5|string',
            ]);

            $salle = Salle::create($validatedData);

            return response()->json([
                "message" => "Salle créée avec succès",
                "data" => $salle
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche une salle spécifique.
     */
    public function show(Salle $salle)
    {
        return response()->json($salle, 200);
    }

    /**
     * Met à jour une salle existante.
     */
    public function update(Request $request, Salle $salle)
    {
        try {
            $validatedData = $request->validate([
                'contenance' => 'required|min:5|string',
                'status' => 'required|min:5|string',
            ]);

            $salle->update($validatedData);

            return response()->json([
                "message" => "Salle mise à jour avec succès",
                "data" => $salle
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime une salle selon son code.
     */
    public function destroy(string $code_salle)
    {
        try {
            $salle = Salle::findOrFail($code_salle);
            $salle->delete();

            return response()->json([
                "message" => "Suppression réussie"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Salle non trouvée"
            ], 404);
        }
    }
}
