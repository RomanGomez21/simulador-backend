<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CalculateStructureDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:structure_details,id',
            'consumo_energia'=>'nullable|decimal:0,3',
            'consumo_potencia'=>'nullable|decimal:0,3',
        ];
    }

    public function messages(): array {
        return [
            'id.required' => "El parámetro id es requerido",
            'id.integer' => "El parámetro id debe ser un entero",
            'id.exists' => "El parámetro id debe ser un valor existente",
            'consumo_energia.decimal'  => 'El parámetro consumo_energia debe ser un número decimal y admite hasta 3 decimales.',
            'consumo_potencia.decimal' => 'El parámetro consumo_potencia debe ser un número decimal y admite hasta 3 decimales',
        ];
    }


    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Se han encontrado errores en la validación.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
