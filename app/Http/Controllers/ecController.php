<?php

namespace App\Http\Controllers;

use App\Models\Ec;
use Illuminate\Http\Request;

class EcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ec =Ec::all();
        return response()->json($ec, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([


                'label_ec' => 'required|min:5|string',
                'code_ec'=>'required|min:5|string|unique:ec,code_ec',
                'description_ec' => 'required|min:5|string',
                'nb_heures_ec'=>'',
                'nb_credit_ec'=>''
                ]);
            $res = ec::create($request->all());
            return response()->json(
                ["message" =>"ec crée avec succes"]
                 , 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Ec $ec)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ec $ec)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_ec)
    {
        try {
            $ec = Ec::findOrFail($code_ec);
            $ec->delete();
            return response()->json(
                ["message"=> "Suppression réussie"],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(["message" =>"Ec non trouvée"], 404);
        }
    }
}
