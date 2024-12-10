<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::included()->filter()->sort()->get();
        return response()->json($companies);
    }

    public function store(Request $request)
    {
      

        $request->validate([
            'nit' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'social_reason' => 'required|max:255',
            'telephone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $company = Company::create($request->all());
        return response()->json($company, 201);
    }

    public function show($id)
    {
        $company = Company::included()->findOrFail($id);
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
           'nit' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'social_reason' => 'required|max:255',
            'telephone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $company->update($request->all());
        return response()->json($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, 204);
    }


    public function getCompany(){
        $companies = Company::all();
        return response()->json($companies);
    }
}
