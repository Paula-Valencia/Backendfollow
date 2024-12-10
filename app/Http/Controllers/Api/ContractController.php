<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
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
        $contracts = Contract::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'Company') {
            $contracts->with('company'); // Carga la relación con la compañía
        }

        return response()->json($contracts->get());
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
            'code' => 'required|integer',
            'type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'id_company' => 'required|exists:companies,id',
            
        ]);

        // Creación del nuevo contrato
        $contract = Contract::create($request->all());

        return response()->json($contract, 201); // Respuesta con código 201
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
        $contract = Contract::findOrFail($id);

        return response()->json($contract);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Contract $contract)
    {
        // Validación de los datos de entrada
        $request->validate([
            'code' => 'required|integer',
            'type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'id_company' => 'required|exists:companies,id',
            
        ]);

        // Actualización del contrato
        $contract->update($request->all());

        return response()->json($contract);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contract $contract)
    {
        // Elimina el contrato
        $contract->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }
}
