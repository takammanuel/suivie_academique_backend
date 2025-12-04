<?php

namespace App\Http\Controllers;

use App\Models\Enseigne;
use Illuminate\Http\Request;

class EnseigneController extends Controller
{
    /**
     * Afficher la liste des enseignements.
     */
    public function index()
    {
        // Récupère toutes les lignes de la table enseigne
        $enseignes = Enseigne::all();
        return response()->json($enseignes, 200);
    }

    /**
     * Créer un nouvel enseignement.
     */
    public function store(Request $request)
    {
        try {
            // Validation des champs
            $validatedData = $request->validate([
                'code_ec'        => 'required|string|exists:ec,code_ec',
                'code_personnel' => 'required|String|exists:personnel,code_personnel',
            ]);

            // Création
            $enseigne = Enseigne::create($validatedData);

            return response()->json([
                "message" => "Enseigne créé avec succès",
                "data"    => $enseigne
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un enseignement spécifique.
     */
    public function show($id)
    {
        try {
            $enseigne = Enseigne::findOrFail($id);
            return response()->json($enseigne, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Enseigne non trouvé"], 404);
        }
    }

    /**
     * Mettre à jour un enseignement.
     */
    public function update(Request $request, $id)
    {
        try {
            $enseigne = Enseigne::findOrFail($id);

            $validatedData = $request->validate([
                'code_ec'        => 'sometimes|string|exists:ec,code_ec',
                'code_personnel' => 'sometimes|integer|exists:personnel,code_personnel',
            ]);

            $enseigne->update($validatedData);

            return response()->json([
                "message" => "Enseigne mis à jour avec succès",
                "data"    => $enseigne
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 500);
        }
    }

    /**
     * Supprimer un enseignement.
     */
    public function destroy($id)
    {
        try {
            $enseigne = Enseigne::findOrFail($id);
            $enseigne->delete();

            return response()->json(["message" => "Suppression réussie"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Enseigne non trouvé"], 404);
        }
    }
}
