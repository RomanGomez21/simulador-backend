<?php
namespace App\Actions;

use App\Models\PeriodYear;
use App\Models\Structure;
use App\Services\StructureService;
use Illuminate\Validation\ValidationException;

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
                                            'structure_details.energy_charges.APE_charge',
                                            'structure_details.energy_charges.energy_price',
                                            'structure_details.step_charges',
                                            'structure_details.subsidies',
                                            'structure_details.energy_injection_charges',
                                            'structure_details.consumptions.injection',)
                                    ->find($last_period_year->period_structure_year->structure_id);
            
            return $this->structureService->generate_JSON_from_structure($last_structure,$data);
        }
        //Cuadro tarifario proyectado (PENDIENTE)


        
        return ['LISTO'];
    }
}