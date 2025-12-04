<?php

namespace App\Http\Controllers;

use App\Models\Ue;
use Illuminate\Http\Request;

class UeController extends Controller
{
    /**
     * Affiche la liste de toutes les UE.
     */
    public function index()
    {
        $ues = Ue::all();
        return response()->json($ues, 200);
    }

    /**
     * Enregistre une nouvelle UE.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'label_ue' => 'required|min:5|string',
                'code_ue' => 'required|min:5|string|unique:ue,code_ue',
                'description_ue' => 'required|min:5|string',
            ]);

            $ue = Ue::create($validatedData);

            return response()->json([
                "message" => "UE créée avec succès",
                "data" => $ue
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche une UE spécifique.
     */
    public function show(Ue $ue)
    {
        return response()->json($ue, 200);
    }

    /**
     * Met à jour une UE existante.
     */
    public function update(Request $request, Ue $ue)
    {
        try {
            $validatedData = $request->validate([
                'label_ue' => 'required|min:5|string',
                'description_ue' => 'required|min:5|string',
            ]);

            $ue->update($validatedData);

            return response()->json([
                "message" => "UE mise à jour avec succès",
                "data" => $ue
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime une UE selon son code.
     */
    public function destroy(string $code_ue)
    {
        try {
            $ue = Ue::findOrFail($code_ue);
            $ue->delete();

            return response()->json([
                "message" => "Suppression réussie"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "UE non trouvée"
            ], 404);
        }
    }
}
