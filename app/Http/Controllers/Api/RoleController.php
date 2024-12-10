<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::included()->filter()->sort()->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_type' => 'required|string|max:255',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = Role::included()->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_type' => 'required|string|max:255',
        ]);

        $role->update($request->all());
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
