<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Regional;
use Illuminate\Http\Request;

class RegionalController extends Controller
{
    public function index(){
        $regionals = Regional::included()->filter()->get();
        return response()->json($regionals);
    }
}
