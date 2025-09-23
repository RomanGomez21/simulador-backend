<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReportAverageConsumptionRequest;
use App\Actions\ReportAverageConsumptionAction;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function average_consumption(ReportAverageConsumptionRequest $request, ReportAverageConsumptionAction $action) {
        try {

            $data=$action->calculate($request->validated());
        
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
}
