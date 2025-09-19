<?php
namespace App\Actions;

use App\Models\PeriodYear;
use App\Models\Structure;
use App\Services\StructureService;

class ShowJsonAction {

    protected $structureService;

    public function __construct(StructureService $structure_service)
        {
            $this->structureService = $structure_service;
        }

    public function show_json(): array {
        //Recurro a la última estructura consolidada o proyectada del sistema. Representa el registro mán reciente de la tabla period_year
        $last_period_year=PeriodYear::orderBy('created_at', 'desc')->first();
        //Última estructura consolidada
        if($last_period_year->period_structure_year->exists) {
            $last_structure=Structure::with('structure_details.subcategory.category',
                                            'structure_details.fixed_charges',
                                            'structure_details.energy_charges.ape_charge',
                                            'structure_details.energy_charges.energy_price',
                                            'structure_details.step_charges',
                                            'structure_details.subsidies',
                                            'structure_details.energy_injection_charges',
                                            'structure_details.consumptions.injection',
                                            )
                                    ->find($last_period_year->period_structure_year->structure_id)->toArray();
            //Convierto la estructua consolidada, a formato de estructura JSON 
            return $this->structureService->convert_JSON($last_structure,$last_period_year->period->description, $last_period_year->year->value);
        } else {
            //Última proyección generada
            $last_period_proyection= (string) $last_period_year->period->id;
            $last_year_proyection=(string) $last_period_year->year->value;
            //Recurro a la ruta de la última proyección generada
            $last_proyection_path = storage_path("app/" . "{$last_period_proyection}_{$last_year_proyection}_structure.json");
            //Tomo el contenido de esa última proyección
            $last_proyection_content = file_get_contents($last_proyection_path); //Obtengo cadena JSON
            $last_proyection_data = json_decode($last_proyection_content, true); //Obtengo un array asociativo de esa última proyección
            return $last_proyection_data;
        }
    }


}