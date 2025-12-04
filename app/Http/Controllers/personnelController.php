<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnel = Personnel::all();
        return response()->json($personnel, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code_personnel'     => 'required|string|max:20|unique:personnel,code_personnel',
                'nom_personnel'      => 'required|string|min:5|max:191',
                'sex_personnel'      => 'required|in:M,F',
                'phone_personnel'    => 'required|digits_between:8,11',
                'login_personnel'    => 'required|string|max:191|unique:personnel,login_personnel',
                'password_personnel' => 'required|string|min:6',
                'type_personnel'     => 'required|in:ENSEIGNANT,RESPONSABLE_ACADEMIQUE,RESPONSABLE_FINANCIER'
            ]);

            $res = Personnel::create($validatedData);

            return response()->json([
                "message" => "Personnel créé avec succès"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        return response()->json($personnel, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        try {
            $validatedData = $request->validate([
                'nom_personnel'  => 'sometimes|string|min:5|max:191',
                'sex_personnel'  => 'sometimes|in:M,F',
                'phone_personnel'=> 'sometimes|digits_between:8,11',
                'login_personnel'=> 'sometimes|string|max:191|unique:personnel,login_personnel,' . $personnel->code_personnel . ',code_personnel',
                'password_personnel'=> 'sometimes|string|min:6',
                'type_personnel'=> 'sometimes|in:ENSEIGNANT,RESPONSABLE_ACADEMIQUE,RESPONSABLE_FINANCIER'
            ]);

            $personnel->update($validatedData);

            return response()->json([
                "message" => "Mise à jour réussie"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_personnel)
    {
        try {
            $personnel = Personnel::findOrFail($code_personnel);
            $personnel->delete();

            return response()->json([
                "message" => "Suppression réussie"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Personnel non trouvé"
            ], 404);
        }
    }
}
