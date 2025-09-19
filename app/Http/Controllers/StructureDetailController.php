<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\ShowStructureDetailAction;
use App\Actions\CalculateStructureDetailAction;
use App\Http\Requests\StructureDetailRequest;
use App\Http\Requests\CalculateStructureDetailRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StructureDetailController extends Controller
{
    public function show(StructureDetailRequest $request, ShowStructureDetailAction $action) {
        try {
            $data = $action->show($request->validated());

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detalle de estructura encontrado',
            ], 200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Detalle de estructura no encontrado'
            ], 404);
        } 
    }

    public function calculate(CalculateStructureDetailRequest $request, CalculateStructureDetailAction $action) {
        try {
            $data=$action->execute($request->validated());

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cálculo exitoso',
            ], 200);    

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Detalle de estructura no encontrado'
            ], 404);
        }

    }

}
