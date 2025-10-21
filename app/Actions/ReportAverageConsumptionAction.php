<?php
namespace App\Actions;

use App\Models\Structure;
use Illuminate\Support\Facades\Storage;
use App\Services\CalculateService;
use App\Models\APECharge;
use Illuminate\Validation\ValidationException;



class ReportAverageConsumptionAction {

     protected $calculateService;

    public function __construct(CalculateService $calculate_service)
        {
            $this->calculateService = $calculate_service;
        }

    public function calculate(array $data ): array {
        $result=[];
        //Referencia
        if($data["reference_type"] =='T') {
            $reference_array=Structure::with('structure_details.subcategory.category',
                                'structure_details.fixed_charges',
                                'structure_details.energy_charges.ape_charge',
                                'structure_details.energy_charges.energy_price',
                                'structure_details.step_charges',
                                'structure_details.subsidies',
                                'structure_details.energy_injection_charges',
                                'structure_details.consumptions.injection')->findOrFail($data["reference_id"])->toArray();
        } else {
            //Año de entrada
            $year_entry=substr($data['reference_id'], -4);
            //Periodo de entrada
            $period_entry = substr($data['reference_id'], 0, -4);

            // Obtener todos los archivos de la carpeta storage/app
            $files = Storage::files('');
            //Filtro por aquellos que son .json y que sean distintos a la estructura de referencia del sistema
            $json_files = collect($files)
                        ->filter(fn($file) => str_ends_with($file, 'structure.json') && basename($file) !== '6_2025_structure.json')
                        ->map(fn($file) => basename($file))
                        ->values()
                        ->toArray();

            //Verifico si la proyección existe
            if (!in_array("{$period_entry}_{$year_entry}_structure.json", $json_files)) {
                throw ValidationException::withMessages([
                    'id' => ["La proyección JSON correspondiente al periodo {$period_entry} y año {$year_entry} no existe."],
                ]);
            }
            //Obtengo su contenido
            $change_path = storage_path("app/{$period_entry}_{$year_entry}_structure.json"); //Recurro a la ruta del cambio
            $change_content = file_get_contents($change_path); //Obtengo cadena JSON
            $reference_array = json_decode($change_content, true);

            $ape_charge=(float) $reference_array['ape_charge']['value'];

            foreach($reference_array['categories'] as &$category) {
                foreach($category['subcategories'] as &$subcategory) {
                    foreach($subcategory['energy_charges'] as &$energy_charge){
                        $json_energy_price=array_filter($reference_array["energy_prices"], function($price) use ($energy_charge) {
                                                return $energy_charge['energy_price_json_id']=== $price["json_id"];
                                            }
                                        ); //este filtro devuelve un array de este formato: array:1 [indice del elemento que pasó el filtro => array:3 [ ... ]]
                        //Es como un array de arrays, solo que tiene un elemento. Entonces utilizo la funcion reset que toma el primer elemento de un array
                        $json_energy_price=reset($json_energy_price);
                        if (array_key_exists('ape_charge_id', $energy_charge)) {
                            $db_ape_charge=APECharge::find($energy_charge['ape_charge_id']);
                            $energy_charge['value']=(($json_energy_price['value']/(1-(3.5/100))+ $db_ape_charge->value))/(1-($energy_charge['energy_loss_percentage']/100));
                        } else {
                            $energy_charge['value']=(($json_energy_price['value']/(1-(3.5/100))+ $ape_charge))/(1-($energy_charge['energy_loss_percentage']/100));
                        }

                    }
                }
            }
 
        }
        //Filtrado del array de referencia por los ids de subcategoría
        if($data["reference_type"] =='T') {
            $filter=$data["reference_subcategory_ids"];
            $reference_array_filtered = collect($reference_array['structure_details'])
                ->filter(function ($detail) use ($filter) {
                    return in_array($detail['subcategory_id'], $filter);
                })
                ->values()->toArray(); // para resetear los índices
        } else {
            $filter=$data["reference_subcategory_ids"];
            $reference_array_filtered=collect($reference_array['categories'])
                ->flatMap(function ($category) {
                    return $category['subcategories'];
                })
                ->filter(function ($subcategory) use ($filter) {
                    return in_array($subcategory['id'], $filter);
                })
                ->values() // resetea los índices
                ->toArray();
        }

        //Llamo a CalculateService con su método para cada caso
        if($data["reference_type"] =='T') {
            $reference_result=[];
            foreach($reference_array_filtered as $structure_detail) {
                $reference_result[]=[
                    "id"=> $structure_detail['subcategory_id'],
                    "result"=>$this->calculateService->calculate_average_consumption_from_T($structure_detail)
                ];
            }
        } else  {
            $reference_result=[];
            foreach($reference_array_filtered as $subcategory) {
                $reference_result[]=[
                    "id"=> $subcategory['id'],
                    "result"=>$this->calculateService->calculate_average_consumption_from_P($subcategory)
                ];

            }
        }
        //---------------------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //APPLIED TO:
        if($data["applied_to"]["type_id"] =='T') {
            $applied_to_array=Structure::with('structure_details.subcategory.category',
                                'structure_details.fixed_charges',
                                'structure_details.energy_charges.ape_charge',
                                'structure_details.energy_charges.energy_price',
                                'structure_details.step_charges',
                                'structure_details.subsidies',
                                'structure_details.energy_injection_charges',
                                'structure_details.consumptions.injection')->findOrFail($data["applied_to"]["id"])->toArray();
        } else {
            //Año de entrada
            $year_entry=substr($data["applied_to"]["id"], -4);
            //Periodo de entrada
            $period_entry = substr($data["applied_to"]["id"], 0, -4);

            // Obtener todos los archivos de la carpeta storage/app
            $files = Storage::files('');
            //Filtro por aquellos que son .json y que sean distintos a la estructura de referencia del sistema
            $json_files = collect($files)
                        ->filter(fn($file) => str_ends_with($file, 'structure.json') && basename($file) !== '6_2025_structure.json')
                        ->map(fn($file) => basename($file))
                        ->values()
                        ->toArray();

            //Verifico si la proyección existe
            if (!in_array("{$period_entry}_{$year_entry}_structure.json", $json_files)) {
                throw ValidationException::withMessages([
                    'id' => ["La proyección JSON correspondiente al periodo {$period_entry} y año {$year_entry} no existe."],
                ]);
            }
            //Obtengo su contenido
            $change_path = storage_path("app/{$period_entry}_{$year_entry}_structure.json"); //Recurro a la ruta del cambio
            $change_content = file_get_contents($change_path); //Obtengo cadena JSON
            $applied_to_array = json_decode($change_content, true);

            $ape_charge=(float) $applied_to_array['ape_charge']['value'];

            foreach($applied_to_array['categories'] as &$category) {
                foreach($category['subcategories'] as &$subcategory) {
                    foreach($subcategory['energy_charges'] as &$energy_charge){
                        $json_energy_price=array_filter($applied_to_array["energy_prices"], function($price) use ($energy_charge) {
                                                return $energy_charge['energy_price_json_id']=== $price["json_id"];
                                            }
                                        ); //este filtro devuelve un array de este formato: array:1 [indice del elemento que pasó el filtro => array:3 [ ... ]]
                        //Es como un array de arrays, solo que tiene un elemento. Entonces utilizo la funcion reset que toma el primer elemento de un array
                        $json_energy_price=reset($json_energy_price);
                        if (array_key_exists('ape_charge_id', $energy_charge)) {
                            $db_ape_charge=APECharge::find($energy_charge['ape_charge_id']);
                            $energy_charge['value']=(($json_energy_price['value']/(1-(3.5/100))+ $db_ape_charge->value))/(1-($energy_charge['energy_loss_percentage']/100));
                        } else {
                            $energy_charge['value']=(($json_energy_price['value']/(1-(3.5/100))+ $ape_charge))/(1-($energy_charge['energy_loss_percentage']/100));
                        }

                    }
                }
            }
 
        }

        //Filtrado del array de referencia por los ids de subcategoría
        if($data["applied_to"]["type_id"] =='T') {
            $filter=$data["reference_subcategory_ids"];
            $applied_to_array_filtered = collect($applied_to_array['structure_details'])
                ->filter(function ($detail) use ($filter) {
                    return in_array($detail['subcategory_id'], $filter);
                })
                ->values()->toArray(); // para resetear los índices
        } else {
            $filter=$data["reference_subcategory_ids"];
            $applied_to_array_filtered=collect($applied_to_array['categories'])
                ->flatMap(function ($category) {
                    return $category['subcategories'];
                })
                ->filter(function ($subcategory) use ($filter) {
                    return in_array($subcategory['id'], $filter);
                })
                ->values() // resetea los índices
                ->toArray();
        }
        
        //Llamo a CalculateService con su método para cada caso
        if($data["applied_to"]["type_id"] =='T') {
            $applied_to_result=[];
            foreach($applied_to_array_filtered as $structure_detail) {
                $applied_to_result[]=[
                    "id"=> $structure_detail['subcategory_id'],
                    "result"=>$this->calculateService->calculate_average_consumption_from_T($structure_detail)
                ];
            }
        } else  {
            $applied_to_result=[];
            foreach($applied_to_array_filtered as $subcategory) {
                $applied_to_result[]=[
                    "id"=> $subcategory['id'],
                    "result"=>$this->calculateService->calculate_average_consumption_from_P($subcategory)
                ];

            }
        }
        // Calculo de impacto
        $tolerance = 0.01; // 0.01 %
        //Variable de impactos porcentuales
        $impact=[];

        foreach ($applied_to_result as &$applied) {
            // buscar referencia con el mismo id
            $ref = collect($reference_result)->firstWhere('id', $applied['id']);
            if (!$ref) continue;

            foreach ($applied['result'] as $key => &$appliedValue) {
                $refValue = $ref['result'][$key];
                if ($refValue == 0) {
                    $diffPercent = null; // evitar división por cero
                } else {
                    $diffPercent = (($appliedValue - $refValue) / $refValue) * 100;
                }

                 // Si está dentro de la tolerancia, lo igualamos al valor de referencia
                if (abs($diffPercent) < $tolerance) {
                    $diffPercent = 0.00;
                    $appliedValue = $refValue;
                }

                $impact[$applied['id']][$key] = number_format($diffPercent, 2, '.', '');
            }
        }

        return [
            'reference'=>$reference_result,
            'applied_to'=>$applied_to_result,
            'impact'=>$impact
        ];

            
    }
}