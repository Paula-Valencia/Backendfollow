<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $rules = [
            'identification'      => 'required',
            'name'                => 'required|string|max:255',
            'last_name'           => 'nullable|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'id_role'             => 'required|integer|exists:roles,id',
            'telephone'           => 'required|string|max:15',
            'address'             => 'required|string|max:255',
            'department'          => 'required|string|max:255',
            'municipality'        => 'required|string|max:255',
            'password'            => 'required|string|min:8',
        ];

        $messages = [
            'identification.required' => 'La identificación es obligatoria.',
            'identification.numeric'  => 'La identificación debe ser un número.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.email'             => 'El formato del correo electrónico no es válido.',
            'email.unique'            => 'El correo ya está registrado.',
            'id_role.required'        => 'El rol es obligatorio.',
            'id_role.exists'          => 'El rol seleccionado no es válido.',
            'password.required'       => 'La contraseña es obligatoria.',
            'password.min'            => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $user = new User;
        $user->identification = $request->identification;
        $user->name           = $request->name;
        $user->last_name      = $request->last_name ?? null;
        $user->email          = $request->email;
        $user->id_role        = $request->id_role;
        $user->telephone      = $request->telephone;
        $user->address        = $request->address;
        $user->department     = $request->department;
        $user->municipality   = $request->municipality;
        $user->password       = bcrypt($request->password);

        $user->save();


        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user'    => $user
        ], 201);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUser(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    protected function respondWithToken($token): JsonResponse
    {
        $user_id = auth('api')->user()->id;
        $user = User::with('role')->findOrFail($user_id);
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $user
        ]);
    }

    /**
     * Check email to send user
     *
     * @return JsonResponse
     */
    public function verifiedEmail(Request $request): JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update(['code_verified' => uniqid()]);
            Mail::to($request->email)->send(new ResetPasswordMail($user));
            return response()->json(['message' => 200]);
        } else {
            return response()->json(['message' => 403]);
        }
    }

    /**
     * Check code to change password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifiedCode(Request $request): JsonResponse
    {

        $user = User::where('code_verified', $request->code)->first();

        if ($user) {
            return response()->json(['message' => 200], 200); // If code is equal to code_verified return 200 OK
        } else {
            return response()->json(['message' => 403], 403);
        }
    }

    /**
     * Change password to user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function newPassword(Request $request): JsonResponse
    {
        $user = User::where('code_verified', $request->code)->first();
        $user->update([
            'password'      => bcrypt($request->newPassword),
            'code_verified' => NULL
        ]);
        return response()->json(['message' => 200]);
    }
}
