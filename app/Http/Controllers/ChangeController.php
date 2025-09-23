<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangeRequest;
use App\Http\Requests\ShowChangeRequest;
use App\Actions\ChangeAction;
use App\Actions\IndexChangeAction;
use App\Actions\ShowChangeAction;
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

    public function show(ShowChangeRequest $request, ShowChangeAction $action) {
        
        try {
            
            $data=$action->show($request->validated());
        
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cuadros Tarifarios consolidados',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Devuelve los errores de validación
            ], 422);
        } 
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
