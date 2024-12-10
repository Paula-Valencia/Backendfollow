<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json();
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
            'number_log' => 'required|integer',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'observation' => 'required|string|max:255',
            'id_trainer' => 'required|exists:trainers,id',
            'id_apprentice' => 'required|exists:apprentices,id',
        ]);

        // Creación del nuevo contrato
        $log = Log::create($request->all());

        return response()->json($log, 201); // Respuesta con código 201
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $log = Log::findOrFail($id);

        return response()->json($log);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Log $log)
    {
        // Validación de los datos de entrada
        $request->validate([
            'number_log' => 'required|integer',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'observation' => 'required|string|max:255',
            'id_trainer' => 'required|exists:trainers,id',
            'id_apprentice' => 'required|exists:apprentices,id',
        ]);

        // Actualización del contrato
        $log->update($request->all());

        return response()->json($log);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Log $log)
    {
        // Elimina el contrato
        $log->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }


    public function getLogsByApprentice(string|int $id): JsonResponse
    {

        $logs = Log::with('apprentice')->whereHas('apprentice', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->get();

        return response()->json($logs);
    }

    public function updateLogsByIds(Request $request): JsonResponse
    {
        $idsLogs = $request->idsLogs;
        $date    = $request->date;
        $state   = $request->state;

        $logsUpdated = Log::whereIn('id', $idsLogs)->update([
            'date'  => $date ?? now(),
            'state' => $state,
        ]);

        return response()->json($logsUpdated);
    }
}
