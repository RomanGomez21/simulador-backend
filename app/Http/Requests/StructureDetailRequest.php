<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StructureDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:structure_details,id',
        ];
    }

    public function messages(): array {
        return [
            'id.required' => "El parámetro id es requerido",
            'id.integer' => "El parámetro id debe ser un entero",
            'id.exists' => "El parámetro id debe ser un valor existente",
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Se han encontrado errores en la validación.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
