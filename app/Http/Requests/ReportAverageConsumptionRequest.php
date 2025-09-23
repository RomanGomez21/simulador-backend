<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReportAverageConsumptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reference_type' => 'required|string|size:1|in:T,P',
            'reference_id'=> 'required|integer',
            'reference_subcategory_ids' => 'required|array',
            'reference_subcategory_ids.*' =>'integer|distinct|exists:subcategories,id',
            'applied_to'=> 'required|array',
            'applied_to.id'=>'required|integer',
            'applied_to.type_id'=>'required|string|size:1|in:T,P'
        ];
    }

    public function messages(): array {
        return [
            //String reference_type
            'reference_type.required' => 'El campo reference_type es obligatorio.',
            'reference_type.string' => 'El campo reference_type debe ser una cadena de texto.',
            'reference_type.size' => 'El campo reference_type debe tener exactamente 1 carácter.',
            'reference_type.in' => 'El campo reference_type debe ser "T" (Tarifa Consolidada) o "P" (Proyección).',
            //Entero reference_id
            'reference_id.required' => "El parámetro reference_id es requerido",
            'reference_id.integer' => "El parámetro reference_id debe ser un entero",
            //Array reference_subcategory_ids
            'reference_subcategory_ids.required' => "El parámetro reference_subcategory_ids es requerido",
            'reference_subcategory_ids.array' => "El parámetro reference_subcategory_ids debe ser un array",
            'reference_subcategory_ids.*.integer'  => 'La subcategoría debe ser un número entero.',
            'reference_subcategory_ids.*.distinct' => 'Las subcategorías deben ser únicas, no se permiten duplicados.',
            'reference_subcategory_ids.*.exists'   => 'La subcategoría no existe en el sistema.',
            //Array applied_to
            'applied_to.required' => "El parámetro applied_to es requerido",
            'applied_to.array' => "El parámetro applied_to debe ser un array",
            'applied_to.id.required' => "El parámetro id es requerido",
            'applied_to.id.integer' => "El parámetro id debe ser un entero",
            'applied_to.type_id.required' => 'El parámetro type_id es obligatorio.',
            'applied_to.type_id.string' => 'El parámetro type_id debe ser una cadena de texto.',
            'applied_to.type_id.size' => 'El parámetro type_id debe tener exactamente 1 carácter.',
            'applied_to.type_id.in' => 'El parámetro type_id debe ser "T" (Tarifa Consolidada) o "P" (Proyección).',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Se han encontrado errores en la validación.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
