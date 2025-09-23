<?php
namespace App\Actions;

use App\Models\Structure;
use Illuminate\Support\Facades\Storage;
use App\Services\CalculateService;
use Illuminate\Validation\ValidationException;



class ReportAverageConsumptionAction {

     protected $calculateService;

    public function __construct(CalculateService $calculate_service)
        {
            $this->calculateService = $calculate_service;
        }

    public function calculate(array $data ): array {
        $result=[];
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

        //APPLIED TO:
        
        

            
    }
}