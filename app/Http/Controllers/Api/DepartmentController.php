<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Obtiene todos los departamentos junto con sus municipios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Traer los departamentos con sus municipios
        $departamentos = Department::with('municipios')->get();

        return response()->json($departamentos);
    }
}
