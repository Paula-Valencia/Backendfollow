<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    
 /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Recupera todos los contratos
        $followups = Followup::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'Trainer') {
            $followups->with('trainer'); // Carga la relación con la compañía
        }

        return response()->json($followups->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'type_of_agreement' => 'required|integer',
            'date' => 'required|date',
            'name_of_immediate_boss' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telephone' => 'required|integer',
            'observation' => 'required|string|max:255',
            'id_trainer' => 'required|exists:trainers,id',
            
        ]);

        // Creación del nuevo contrato
        $followup = Followup::create($request->all());

        return response()->json($followup, 201); // Respuesta con código 201
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera un contrato específico
        $followup = Followup::findOrFail($id);

        return response()->json($followup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Followup $followup)
    {
        // Validación de los datos de entrada
        $request->validate([
            'type_of_agreement' => 'required|integer',
            'date' => 'required|date',
            'name_of_immediate_boss' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telephone' => 'required|integer',
            'observation' => 'required|string|max:255',
            'id_trainer' => 'required|exists:trainers,id',
        ]);

        // Actualización del contrato
        $followup->update($request->all());

        return response()->json($followup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Followup $followup)
    {
        // Elimina el contrato
        $followup->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }
}
