<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangeRequest;
use App\Actions\ChangeAction;
use App\Actions\IndexChangeAction;
use Illuminate\Validation\ValidationException;

class ChangeController extends Controller
{
    public function index(IndexChangeAction $action) {
        
        $data=$action->index();
        
        return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cuadros Tarifarios consolidados',
        ], 200);
    }
    
    
    public function change(ChangeRequest $request, ChangeAction $action) {
        try {

            $data= $action->change($request->all());

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Estructura generada correctamente',
            ], 200);
        }

        catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Devuelve los errores de validación
            ], 422);
        }  
    }
}
