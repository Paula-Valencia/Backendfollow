<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifiedMail;
use App\Models\Apprentice;
use App\Models\Contract;
use App\Models\Followup;
use App\Models\Notification;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Log;


class ApprenticeController extends Controller
{
    public function index(Request $request)
    {
        $apprentices = Apprentice::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $apprentices->with('userRegister'); // Carga la relación con la compañía
        }

        return response()->json($apprentices->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'academic_level' => 'required|max:255',
            'program' => 'required|max:255',
            'ficha' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'id_contract' => 'required|exists:contracts,id',
            'id_trainer' => 'required|exists:trainers,id', // Cambiado a followup_id
        ]);

        $apprentice = Apprentice::create($request->all());
        return response()->json($apprentice, 201); // Respuesta con código 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera una agenda en específico, aplicando el scope de inclusión
        $apprentice = Apprentice::included()->findOrFail($id);
        return response()->json($apprentice);
    }

    public function getUserApprenticeById(string|int $id): JsonResponse
    {

        $apprentice = User::with(['apprentice.contract.Company'])->findOrFail($id);

        return response()->json($apprentice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Apprentice $apprentice)
    {
        // Validación de los datos de entrada
        $request->validate([
            'academic_level' => 'required|max:255',
            'program' => 'required|max:255',
            'ficha' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'id_contract' => 'required|exists:contracts,id',
            'id_trainer' => 'required|exists:trainers,id', // Cambiado a followup_id
        ]);

        // Actualización de agenda
        $apprentice->update($request->all());
        return response()->json($apprentice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Apprentice $apprentice)
    {
        // Elimina agenda
        $apprentice->delete();
        return response()->json(null, 204); // Respuesta vacía con código 204
    }

    public function asignarInstructorAprendiz(Request $request)
    {
        DB::beginTransaction();
        try {
            // Crear usuario
            $user = User::create([
                'identification' => $request->identification,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'address' => $request->address,
                'department' => $request->department,
                'municipality' => $request->municipality,
                'password' => bcrypt('sena@2024'),
                'id_role' => 4,
            ]);
    
            // Crear contrato
            $contract = Contract::create([
                'code' => rand(1000, 9999),
                'type' => 'default',
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'id_company' => $request->id_company,
            ]);
    
            // Crear aprendiz
            $apprentice = Apprentice::create([
                'academic_level' => $request->academic_level,
                'program' => $request->program,
                'ficha' => $request->ficha,
                'user_id' => $user->id,
                'id_contract' => $contract->id,
                'id_trainer' => $request->id_trainer,
                'modalidad' => $request->modalidad,
            ]);

            foreach (range(1, 12) as $key) {
                Log::create([
                    'number_log'    => $key,
                    'description'   => 'description',
                    'date'          => now(),
                    'observation'   => 'observation',
                    'id_trainer'    => $request->id_trainer,
                    'id_apprentice' => $apprentice->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
    
            // Obtener el usuario autenticado
            $authUser = auth('api')->user();
    
            // Notificación al aprendiz
            $messageToApprentice = "Has sido registrado exitosamente como aprendiz. Bienvenido(a), {$user->name} {$user->last_name}.";
            Notification::create([
                'shipping_date' => now(),
                'content' => 'Registro completado',
                'message' => $messageToApprentice,
                'user_id' => $user->id,
                'sender_id' => $authUser->id,
            ]);



            $trainer = Trainer::where('id', $request->id_trainer)->first();

            if ($trainer) {
                $trainerUser = User::find($trainer->user_id);
            
                if ($trainerUser) {
                    $messageToTrainer = "Se te ha asignado un nuevo aprendiz: {$user->name} {$user->last_name}.";
                    Notification::create([
                        'shipping_date' => now(),
                        'content' => 'Nuevo aprendiz asignado',
                        'message' => $messageToTrainer,
                        'user_id' => $trainerUser->id,  
                        'sender_id' => $authUser->id,
                    ]);
                }
            }
    
            // Enviar correo al aprendiz
            Mail::to(request()->email)->queue(new VerifiedMail($user));
    
            DB::commit();
            return response()->json(['message' => 'Aprendiz registrado exitosamente.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al registrar el aprendiz: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get last trainer assigned to apprentice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainerByApprentice(): JsonResponse
    {
        // Obtener el usuario autenticado
        $user = auth('api')->user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Buscar el último aprendiz relacionado con el usuario autenticado
        $apprentice = Apprentice::with([
            'trainer.user',
            'trainer.followUps'
        ])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        // Verificar si se encontró un aprendiz
        if (!$apprentice) {
            return response()->json(['message' => 'No apprentice found for this user.'], 404);
        }

        return response()->json($apprentice);
    }

    public function getApprenticesByModalidad()
    {
        $apprenticesByModalidad = Apprentice::select('modalidad', DB::raw('count(*) as count'))
            ->groupBy('modalidad')
            ->get();

        return response()->json($apprenticesByModalidad);
    }

    public function getAllFollowUpsByApprentice(Request $request): JsonResponse
    {
        $user = auth('api')->user();
        $user_id = $request->id_apprentice ?? $user->id;

        $followUps = Followup::whereHas('trainer.apprentices', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->get();

        return response()->json($followUps);
    }

    public function updateEstado(Request $request, $user_id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:activo,novedad,finalizada',
        ]);
        $apprentice = Apprentice::where('user_id', $user_id)->first();

        if (!$apprentice) {
            return response()->json(['error' => 'Apprentice not found'], 404);
        }
        $apprentice->estado = $validated['estado'];
        $apprentice->save();

        return response()->json(['message' => 'Estado actualizado correctamente'], 200);
    }

    public function getLogByAprentice(): JsonResponse
    {
        $user = auth('api')->user();

        $logs = Log::with('apprentice')->whereHas('apprentice', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json($logs);
    }
}
