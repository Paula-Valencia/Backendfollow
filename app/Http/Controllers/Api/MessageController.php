<?php

namespace App\Http\Controllers\Api;

use App\Models\Message; // Asegúrate de importar el modelo correcto
use Illuminate\Http\Request;

class MessageController extends Controller
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
        $messages = Message::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $messages->with('userRegister'); // Carga la relación con la compañía
        }
  
        return response()->json($messages->get());
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
            'message' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
        ]);

        // Creación del nuevo contrato
        $message = Message::create($request->all());

        return response()->json($message, 201); // Respuesta con código 201
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
        $message = Message::findOrFail($id);

        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Message $message)
    {
        // Validación de los datos de entrada
        $request->validate([
            'message' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
        ]);

        // Actualización del contrato
        $message->update($request->all());

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Message $message)
    {
        // Elimina el contrato
        $message->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }
}

