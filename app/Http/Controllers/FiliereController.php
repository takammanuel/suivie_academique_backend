<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Correction : get() au lieu de get
        $filieres = Filiere::with("niveaux")->get();
        return response()->json($filieres, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code_filiere'  => 'required|min:5|string|unique:filiere,code_filiere',
                'label_filiere' => 'required|min:5|string',
                'description_filiere' => 'required|min:5|string'

            ]);

            $res = Filiere::create($validatedData);

            return response()->json(
                ["message" => "Filiere créée avec succès", "data" => $res],
                201
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_filiere)
    {
        try {
            $filiere = Filiere::with("niveaux")->findOrFail($code_filiere);
            return response()->json($filiere, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Filiere non trouvée"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_filiere)
    {
        try {
            $filiere = Filiere::findOrFail($code_filiere);

            $validatedData = $request->validate([
                'label_filiere' => 'sometimes|string|min:5',
                // ajoute d’autres règles selon tes colonnes
            ]);

            $filiere->update($validatedData);

            return response()->json([
                "message" => "Filiere mise à jour avec succès",
                "data"    => $filiere
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_filiere)
    {
        try {
            $filiere = Filiere::findOrFail($code_filiere);
            $filiere->delete();
            return response()->json(
                ["message" => "Suppression réussie"],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(["message" => "Filiere non trouvée"], 404);
        }
    }
}
