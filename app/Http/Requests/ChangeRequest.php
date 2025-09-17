<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ChangeRequest extends FormRequest
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
            'year_id'=> 'required|integer|exists:years,id',
            'changes'=> 'required|array',
            'changes.*.type'=>[
                'required',
                'string',
                Rule::in(['replicate','increase_%','increase_C','addition','removal','energy_price']),
            ],
            
        ];
    }

    public function withValidator($validator) {
        //El método after ejecuta una función luego de que se ejecuten las validaciones del metodo rules(), independiente si alguna de estas ultimas
        //falla o no
        $validator->after(
            function ($validator) {
                foreach ($this->input('changes') as $index => $change) {
                    $type=$change['type'];
                    //Casos para cambios de tipo increase_% y increase_C
                    if(in_array($type,['increase_%','increase_C'])) {
                        //Parámetro type_charge recibido
                        if (!array_key_exists('type_charge',$change)) {
                            $validator->errors()->add("changes.$index.type_charge", "El campo type_charge es requerido cuando el campo type es de tipo $type");      
                        } else {
                            if(!in_array($change['type_charge'],['fixed', 'step', 'subsidy', 'APE'])) {
                                $validator->errors()->add("changes.$index.type_charge", "El campo type_charge debe ser fixed,step,subsidy o APE cuando el campo type es de tipo $type");
                            }
                        }
                        //Parámtro value requerido
                        if (!array_key_exists('value',$change)) {
                            $validator->errors()->add("changes.$index.value", "El campo value es requerido cuando el campo type es de tipo $type");
                        } else {
                            
                            if($type === 'increase_%') {
                                //Parámetro value numérico
                                if(!is_numeric($change['value'])) {
                                    $validator->errors()->add("changes.$index.value", "El campo value debe ser un decimal cuando el campo type es de tipo $type");
                                } else {
                                    // Parámetro value decimal entre 0 y 100
                                    if($change['value']<-200 || $change['value']>200) {
                                        $validator->errors()->add("changes.$index.value", "El campo value estar entre -200 y 200 cuando el campo type es de tipo $type");
                                    }
                                }
                            } 

                            if($type === 'increase_C') {
                                //Parámetro value numérico
                                if(!is_numeric($change['value'])) {
                                    $validator->errors()->add("changes.$index.value", "El campo value debe ser un decimal cuando el campo type es de tipo $type");
                                } else {
                                    // Parámetro value de hasta 20 digitos y 3 decimales
                                    if (!preg_match('/^-?\d{1,17}(\.\d{1,3})?$/', (string)$change['value'])) {
                                        $validator->errors()->add("changes.$index.value", "El campo value puede tener hasta 20 digitos y solo acepta hasta 3 decimales cuando el campo type es de tipo $type ");
                                    }
                                }
                            }        
                        }
                        //Parámetro filter
                        if (array_key_exists('filter', $change)) {
                            if (!is_array($change['filter'])) {
                                $validator->errors()->add("changes.$index.filter", "El campo filter debe ser un array de cargos cuando el campo type es de tipo $type");
                            }
                        }
                        //Parámetro except_to
                        if (array_key_exists('except_to', $change)) {
                            if (!is_array($change['except_to'])) {
                                $validator->errors()->add("changes.$index.except_to", "El campo except_to debe ser un array de categorías cuando el campo type es de tipo $type");
                            }
                        }    
                    } //FIN del if para los casos de increase_C y increase_%
                    //Casos para cambios de tipo addition o removal
                    if(in_array($type,['addition'])) {
                        //Parámetro type_charge recibido
                        if (!array_key_exists('type_charge',$change)) {
                            $validator->errors()->add("changes.$index.type_charge", "El campo type_charge es requerido cuando el campo type es de tipo $type");    
                        } else {
                            $type_charge=$change['type_charge'];
                            if(!in_array($type_charge,['fixed','energy','step','subsidy'])) {
                                $validator->errors()->add("changes.$index.type_charge", "El campo type_charge debe ser fixed, energy, step o subsidy cuando el campo type es de tipo $type");
                            } else {
                                //Parámetros para el caso de añadir un cargo fijo
                                if($type_charge === 'fixed') {
                                    //Parámetro description requerido
                                    if (!array_key_exists('description',$change) || !is_string($change['description'])) {
                                        $validator->errors()->add("changes.$index.description", "El campo description es requerido y debe ser una cadena de texto");
                                    }
                                    //Parámetro value requerido
                                    if (!array_key_exists('value',$change) || !preg_match('/^-?\d{1,17}(\.\d{1,3})?$/', (string)$change['value'])) {
                                        $validator->errors()->add("changes.$index.value", "El campo value es requerido y puede tener hasta 20 digitos y solo acepta hasta 3 decimales cuando el campo type es de tipo $type y el campo type_charge es $type_charge");
                                    }
                                }
                                //Parámetros para el caso de añadir un concepto de energía (PENDIENTE)
                            }
                        }
                    }
                    // Cambios de tipo energy_prices
                    if(in_array($type,['energy_price'])) {
                        //Parámetro energy_prices
                        if (array_key_exists('energy_prices', $change)) {
                            if (!is_array($change['energy_prices'])) {
                                $validator->errors()->add("changes.$index.energy_prices", "El campo energy_prices debe ser un array de monómicos cuando el campo type es de tipo $type");
                            } else {
                                foreach($change['energy_prices'] as $j=>$energy_price) {
                                    if(!array_key_exists('description',$energy_price) || !is_string($energy_price['description'])) {
                                        $validator->errors()->add("changes.$index.energy_prices.$j.description", "El campo description es requerido y debe ser una cadena de texto");
                                    }
                                    if (!array_key_exists('value',$energy_price) || !preg_match('/^-?\d{1,17}(\.\d{1,3})?$/', (string)$energy_price['value'])) {
                                            $validator->errors()->add("changes.$index.energy_prices.$j.value", "El campo value es requerido y puede tener hasta 20 digitos y solo acepta hasta 3 decimales");
                                    }   
                                }
                            } 
                        }
                        
                        //Parámetro json_structure
                        if (array_key_exists('json_structure', $change)) {
                            if (!is_array($change['json_structure'])) {
                                $validator->errors()->add("changes.$index.json_structure", "El campo json_structure debe ser un array de toda la estructura cuando el campo type es de tipo $type");
                            }
                        }
                    }
                }
            }
        );
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Se han encontrado errores en la validación.',
            'errors' => $validator->errors(),
        ], 422));
    }

}
