<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\FollowupController;
use App\Http\Controllers\Api\ApprenticeController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserRegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'getUser']);
Route::post('verified_email', [AuthController::class, 'verifiedEmail']);
Route::post('verified_code', [AuthController::class, 'verifiedCode']);
Route::post('new_password', [AuthController::class, 'newPassword']);

// Rutas para apprentice
Route::get('apprentices', [ApprenticeController::class, 'index'])->name('api.apprentices.index');
Route::post('apprentices', [ApprenticeController::class, 'store'])->name('api.apprentice.store');
Route::get('apprentices/{apprentice}', [ApprenticeController::class, 'show'])->name('api.apprentice.show');
Route::put('apprentices/{apprentice}', [ApprenticeController::class, 'update'])->name('api.apprentice.update');
Route::delete('apprentices/{apprentice}', [ApprenticeController::class, 'destroy'])->name('api.apprentice.delete');

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('get_all_follow_ups_by_apprentice', [ApprenticeController::class, 'getAllFollowUpsByApprentice']);
    Route::get('get_trainer_assigned_by_apprentice', [ApprenticeController::class, 'getTrainerByApprentice']);
    Route::get('get_apprentices_by_instructor', [TrainerController::class, 'getApprenticesByTrainner']);
    Route::get('get_apprentice_by_user_id/{id}', [ApprenticeController::class, 'getUserApprenticeById']);
    Route::get('get_logs_apprentice', [ApprenticeController::class, 'getLogByAprentice']);
    Route::post('create_notification', [TrainerController::class, 'createNotification']);
    Route::get('get_received_notifications_by_user', [NotificationController::class, 'getReceivedNotifications']);
    Route::get('get_notifications_send_by_user', [NotificationController::class, 'getNotificationsSend']);

    Route::get('get_visits_by_apprentice/{id}', [TrainerController::class, 'getVisitsByApprentice']);
    Route::post('create_visit_to_apprentice', [TrainerController::class, 'createVisitToApprentice']);
    Route::put('update_visit_to_apprentice_by_id/{id}', [TrainerController::class, 'updateVisitToApprenticeById']);
    Route::delete('delete_visit/{id}', [TrainerController::class, 'deleteVisit']);

    Route::get('get_logs_by_apprentice/{id}', [LogController::class, 'getLogsByApprentice']);
    Route::put('update_logs_by_ids', [LogController::class, 'updateLogsByIds']);

    Route::get('get_all_follow_ups_by_instructor', [TrainerController::class, 'getAllVisitsByInstructor']);
});

Route::apiResource('logs', LogController::class);
Route::apiResource('companies', CompanyController::class);
Route::apiResource('messages', MessageController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('trainers', TrainerController::class);
Route::apiResource('followups', FollowupController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('user_registers', UserRegisterController::class);
Route::apiResource('contracts', ContractController::class);


Route::get('/departamentos', [DepartmentController::class, 'index']);

Route::get('user_by_roles', action: [UserRegisterController::class, 'getUserRegistersByRoles']);
Route::get('user_by_roles_instructor', action: [UserRegisterController::class, 'getUserRegistersByRolesInstructor']);

Route::get('user_by_roles_aprendiz', action: [UserRegisterController::class, 'getUserRegistersByAprendiz']);
Route::get('get_trainer', action: [UserRegisterController::class, 'getTrainer']);

// Route::post('/apprentices-asignar', [ApprenticeController::class, 'asignarInstructorAprendiz']);

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/apprentices-asignar', [ApprenticeController::class, 'asignarInstructorAprendiz']);
    Route::get('/notification_by_person', [NotificationController::class, 'obtenerNotificacionesUsuario']);
    Route::get('/get_user_data', [UserRegisterController::class, 'obtenerUsuarioAutenticado']);
    Route::post('/store_profile_photo', [UserRegisterController::class, 'storePhotoProfile']);

});

Route::get('getCompany', action: [CompanyController::class, 'getCompany']);


Route::get('user_by_id/{id}', action: [UserRegisterController::class, 'getDataUserById']);

Route::get('apprentices_by_modalidad', [ApprenticeController::class, 'getApprenticesByModalidad']);


Route::put('update_user/{id}', [UserRegisterController::class, 'updateUser']);
Route::delete('delete_user/{id}', [UserRegisterController::class, 'eliminarUser']);
// Si es una API
Route::put('apprentices/{user_id}/estado', [ApprenticeController::class, 'updateEstado']);
