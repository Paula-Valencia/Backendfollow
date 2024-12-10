<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    //
    public function index()
    {
        $trainingCenter = Trainer::included()->filter()->get();
        return response()->json($trainingCenter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:100',
        ]);

        $trainingCenter = Trainer::create($request->all());

        return response()->json($trainingCenter);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) //si se pasa $id se utiliza la comentada
    {
        $trainingCenter = Trainer::included()->findOrFail($id);
        return response()->json($trainingCenter);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trainer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trainer $trainingCenter)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $trainingCenter->update($request->all());

        return response()->json($trainingCenter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trainer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trainer $trainingCenter)
    {
        $trainingCenter->delete();
        return response()->json($trainingCenter);
    }
}
