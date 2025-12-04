<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    /**
     * Affiche tous les programmes.
     */
    public function index()
    {
        $programmes = Programme::all();
        return response()->json($programmes, 200);
    }

    /**
     * Enregistre un nouveau programme.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code_ec' => 'required|min:5|string',
                'code_salle' => 'required|min:5|string',
                'code_personel' => 'required|integer',
                'date' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i',
                'nombre_dheure' => 'required|integer',
                'statut' => 'required|string'
            ]);

            $programme = Programme::create($validatedData);

            return response()->json([
                "message" => "Programme créé avec succès",
                "data" => $programme
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un programme spécifique.
     */
    public function show(Programme $programme)
    {
        return response()->json($programme, 200);
    }

    /**
     * Met à jour un programme existant.
     */
    public function update(Request $request, Programme $programme)
    {
        try {
            $validatedData = $request->validate([
                'code_ec' => 'required|min:5|string',
                'code_salle' => 'required|min:5|string',
                'code_personel' => 'required|integer',
                'date' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i',
                'nombre_dheure' => 'required|integer',
                'statut' => 'required|string'
            ]);

            $programme->update($validatedData);

            return response()->json([
                "message" => "Programme mis à jour avec succès",
                "data" => $programme
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un programme selon son identifiant.
     */
    public function destroy(string $code_programme)
    {
        try {
            $programme = Programme::findOrFail($code_programme);
            $programme->delete();

            return response()->json([
                "message" => "Suppression réussie"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Programme non trouvé"
            ], 404);
        }
    }
}
