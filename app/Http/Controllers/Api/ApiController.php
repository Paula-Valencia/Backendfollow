<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getUsers()
    {
        // Obtener la URL base de la API desde la configuración
        $apiUrl = config('api.base_url');

        // Realizar una solicitud GET a la API de usuarios
        $response = Http::get($apiUrl . '/api/users');

        // Verificar si la respuesta es exitosa
        if ($response->successful()) {
            // Obtener los datos de la respuesta
            $users = $response->json();
            return view('users.index', compact('users'));
        }

        // Si la respuesta falla, devolver un mensaje de error
        return response()->json(['error' => 'No se pudo obtener los usuarios'], 500);
    }
}
