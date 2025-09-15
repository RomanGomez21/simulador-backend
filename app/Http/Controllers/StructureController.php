<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStructureRequest;
use App\Actions\StoreStructureAction;
use App\Actions\IndexStructureAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class StructureController extends Controller
{
    public function store(StoreStructureRequest $request, StoreStructureAction $action ) {
        try {
            $data = $action->store($request->validated());

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Estructura creada correctamente',
            ], 200);
        }

        catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Devuelve los errores de validación
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=> false,
                'message' => 'No es posible consolidar un cuadro tarifario porque no se generó una estructura JSON para ese periodo y año'
            ], 404);
        }  
    }  

    public function index(IndexStructureAction $action) {
        
        $data=$action->index();
        
        return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cuadros Tarifarios consolidados',
        ], 200);
    }

}
