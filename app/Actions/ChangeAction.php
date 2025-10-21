<?php
namespace App\Actions;

use App\Models\PeriodYear;
use App\Models\Structure;
use App\Services\StructureService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ChangeAction {

    protected $structureService;

    public function __construct(StructureService $structure_service)
        {
            $this->structureService = $structure_service;
        }
    
    public function change(array $data): array {
     
        $is_period_year_defined=PeriodYear::where('period_id', $data['period_id'])
                                ->where('year_id', $data['year_id'])
                                ->exists();
        
        if($is_period_year_defined) {
            $period_year_db=PeriodYear::where('period_id', $data['period_id'])
                            ->where('year_id', $data['year_id'])
                            ->first();
            if($period_year_db->period_structure_year->exists) {
                throw ValidationException::withMessages([
                    'conflicto' => "No es posible generar un cuadro tarifario de proyección porque ya existe una tarifa consolidada para {$period_year_db->period->description} {$period_year_db->year->value} ",
                ]);
            }
            if($period_year_db->change->exists){
                throw ValidationException::withMessages([
                    'conflicto' => "Ya existe una estructura proyectada para {$period_year_db->period->description} {$period_year_db->year->value} ",
                ]);
            }
        }
        //Recurro a la última estructura consolidada o proyectada del sistema. Representa el registro mán reciente de la tabla period_year
        $last_period_year=PeriodYear::orderBy('created_at', 'desc')->first();
        //Caso cuadro tarifario consolidado
        if($last_period_year->period_structure_year->exists) {
            $last_structure=Structure::with('structure_details.subcategory.category',
                                            'structure_details.fixed_charges',
                                            'structure_details.energy_charges.ape_charge',
                                            'structure_details.energy_charges.energy_price',
                                            'structure_details.step_charges',
                                            'structure_details.subsidies',
                                            'structure_details.energy_injection_charges',
                                            'structure_details.consumptions.injection',)
                                    ->find($last_period_year->period_structure_year->structure_id)->toArray();
            //Periodo de la última tarifa consolidada
            $last_period_structure=$last_period_year->period->description;
            //Año de la última tarifa consolidada
            $last_year_structure= (string) $last_period_year->year->value;
            //////////////////////
            /////////////////////
            
            //Convertimos a JSON la tarifa consolidada 
            $last_structure_JSON= $this->structureService->convert_JSON($last_structure, $last_period_structure, $last_year_structure);
            //
            $JSON_FORMAT = json_encode($last_structure_JSON, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            
            Storage::put('PRUEBA.json', $JSON_FORMAT);
            dd('LISTO');
            /*Si hay un cambio de monomicos pasan dos cosas:
            1) Si la cantidad se conserva se pasa $last_structure
            2) Si la cantidad no se conserva se pasa el parámetro json_structure del cambio de tipo 'energy_price'
            */
            //foreach ($data['changes'] as $index=>$change) {
                /*
                if (in_array($change['type'],['energy_price'])) {
                    if (array_key_exists('json_structure', $change)) {
                        return $this->structureService->generate_JSON(
                            $change['json_structure'],
                            $data,
                            $last_period_structure,
                            $last_year_structure
                        );
                    }
                }
                */ 
            //}

            return $this->structureService->generate_JSON(
                $this->structureService->convert_JSON($last_structure),
                $data,
                $last_period_structure,
                $last_year_structure
            );

            
            
            
        }
        //Cuadro tarifario proyectado (PENDIENTE)


        
        return ['LISTO'];
    }
}