<?php
namespace App\Actions;

use App\Models\PeriodYear;
use App\Services\StructureService;
use Illuminate\Validation\ValidationException;


class StoreStructureAction {
    
    protected $structureService;

    public function __construct(StructureService $structure_service)
        {
            $this->structureService = $structure_service;
        }

    public function store (array $data): array 
    {
        //Valido que no exista una estructura consolidada para el periodo y año recibido
        $period_year_in_db = PeriodYear::where('period_id', $data['period_id'])
                                        ->where('year_id', $data['year_id'])
                                        ->firstOrFail();

        if($period_year_in_db->period_structure_year) {
            throw ValidationException::withMessages([
                'message' => "Ya existe un cuadro tarifario consolidado para el periodo y año ",
            ]);
        }

        $period_id = $period_year_in_db->period->id;
        $year_value = $period_year_in_db->year->value;    
        $filename = $period_id . "_" . $year_value . "_structure.json";      

        $structure_path = storage_path("app\\" . $filename);
        $structure_content = file_get_contents($structure_path); //Obtengo cadena JSON
        $structure_data = json_decode($structure_content, true); //Obtengo un array asociativo de la estructura del sistema
        
        $result=$this->structureService->store($structure_data);

        return $result->toArray();
    }
}