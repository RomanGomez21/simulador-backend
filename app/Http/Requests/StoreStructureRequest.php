<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStructureRequest extends FormRequest
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
            'period_id'=> 'required|integer|exists:periods,id',
            'year_id'=> 'required|integer|exists:years,id'
        ];
    }

    public function messages(): array {
        return [
            'period_id.required' => "El parámetro period_id es requerido",
            'period_id.integer' => "El parámetro period_id debe ser un entero",
            'period_id.exists' => "El parámetro period_id debe ser un valor entre 1 y 12",
            'year_id.required' => "El parámetro year_id es requerido",
            'year_id.integer' => "El parámetro year_id debe ser un entero",
            'year_id.exists' => "El parámetro year_id debe ser un valor entre 2000 y 2100",
        ];
    }
}
