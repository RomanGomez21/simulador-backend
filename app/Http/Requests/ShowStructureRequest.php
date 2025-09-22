<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ShowStructureRequest extends FormRequest
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:structures,id',
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
