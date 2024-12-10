<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Followup;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TrainerController extends Controller
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
        $trainers = Trainer::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $trainers->with('userRegister'); // Carga la relación con la compañía
        }

        return response()->json($trainers->get());
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
            'number_of_monitoring_hours' => 'required|integer',
            'month' => 'required|date',
            'number_of_trainees_assigned' => 'required|integer',
            'network_knowledge' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        // Creación del nuevo contrato
        $trainer = Trainer::create($request->all());

        return response()->json($trainer, 201); // Respuesta con código 201
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
        $trainer = Trainer::findOrFail($id);

        return response()->json($trainer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Trainer $trainer)
    {
        // Validación de los datos de entrada
        $request->validate([
            'number_of_monitoring_hours' => 'required|integer',
            'month' => 'required|date',
            'number_of_trainees_assigned' => 'required|integer',
            'network_knowledge' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        // Actualización del contrato
        $trainer->update($request->all());

        return response()->json($trainer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Trainer $trainer)
    {
        // Elimina el contrato
        $trainer->delete();

        return response()->json(null, 204);
    }

    /**
     * Get apprentices by trainner
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprenticesByTrainner(): JsonResponse
    {
        $user = auth('api')->user();
        $apprentices = Trainer::with(['apprentices.user'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json($apprentices);
    }

    /**
     * Create new notification by trainner
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNotification(Request $request): JsonResponse
    {
        $authUser = auth('api')->user();

        $notification = Notification::create([
            'shipping_date' => now(),
            'content'       => 'Nueva notificación',
            'message'       => $request->message,
            'user_id'       => $request->user_id,
            'sender_id'     => $authUser->id,
        ]);
        return response()->json($notification);
    }

    /**
     * Get all visits by apprentice
     * @param string|int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVisitsByApprentice(string|int $id): JsonResponse
    {

        $followUps = Followup::with('trainer')->whereHas('trainer.apprentices', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->orderBy('id', 'desc')
            ->get();

        return response()->json($followUps);
    }

    /**
     * Create new visit by apprentice
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createVisitToApprentice(Request $request): JsonResponse
    {
        $apprendice_id = $request->apprendice_id;
        $trainner = Trainer::whereHas('apprentices', function ($query) use ($apprendice_id) {
            $query->where('user_id', $apprendice_id);
        })->latest()->first();
        $request->request->add(['id_trainer' => $trainner->id]);
        unset($request->apprendice_id);
        $followUp = Followup::create($request->all());
        return response()->json($followUp, 201);
    }

    public function updateVisitToApprenticeById(Request $request, string|int $id): JsonResponse
    {
        $followUp = Followup::findOrFail($id);
        $followUp->update($request->all());
        return response()->json($followUp);
    }

    public function deleteVisit(string|int $id): JsonResponse
    {
        $followUp = Followup::findOrFail($id);
        $followUp->delete();
        return response()->json(null, 204);
    }

    public function getAllVisitsByInstructor(): JsonResponse
    {

        $user = auth('api')->user();

        $followUps = Followup::whereHas('trainer', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json($followUps);
    }
}
