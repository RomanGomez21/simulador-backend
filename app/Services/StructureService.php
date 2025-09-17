<?php
namespace App\Services;

use App\Models\Structure;
use App\Models\StructureDetail;
use App\Models\Subsidy;
use App\Models\FixedCharge;
use App\Models\EnergyPrice;
use App\Models\APECharge;
use App\Models\EnergyCharge;
use App\Models\StepCharge;
use App\Models\EnergyInjectionCharge;
use App\Models\Consumption;
use App\Models\Injection;
use App\Models\Year;
use App\Models\PeriodYear;
use App\Models\PeriodStructureYear;
use Illuminate\Support\Facades\Storage; //Para guardar el JSON en storage/app
use App\Models\Category;

class StructureService {

    public function store(array $data): Structure {
        //Guardo en tabla period_year
        $new_period_year=PeriodYear::create(
            [
                "period_id"=> $data['period'], 
                "year_id"=> Year::where('value',$data['year'])->value('id'),
            ]
        );
        //Defino la descripción del nuevo cuadro tarifario
        $new_structure_description="Cuadro Tarifario {$new_period_year->period->description} {$new_period_year->year->value}";
        //Guardo en la tabla structures
        $new_structure=Structure::create(
            [
                "description"=>$new_structure_description
            ]
        );
        //Vinculo en la tabla period_structure_year el cuadro tarifario con el par año-mes
        PeriodStructureYear::create(
            [
                "structure_id"=> $new_structure->id,
                "period_year_id"=>$new_period_year->id
            ]
        );
        //VAD de APE
        $new_ape_charge= APECharge::create(
            [
                "description"=> "VAD de APE {$new_period_year->period->description} {$new_period_year->year->value}" ,
                "value"=>$data["ape_charge"]["value"]
            ]
        );
        //Monómicos de Energía
        foreach($data['energy_prices'] as &$energy_price) { //El signo & indica que estoy iterando sobre los elementos
        //Y al mismo tiempo modificando el array original
            $new_energy_price=EnergyPrice::create(
                    [
                        "description"=>$energy_price['description'],
                        "value"=>$energy_price['value']
                    ]
            );
            $energy_price['db_id']=$new_energy_price->id;
        }
        //Recorro el JSON guardando las subcategorías del cuadro tarifario y sus conceptos de tarifa
        foreach($data['categories'] as $category) {
            foreach($category['subcategories'] as $subcategory) {
                $new_structure_detail=StructureDetail::create(
                    [
                        "structure_id"=>$new_structure->id,
                        "subcategory_id"=>$subcategory['id']
                    ]
                );
                //Fixed Charges
                foreach($subcategory['fixed_charges'] as $fixed_charge) {
                    $new_fixed_charge=FixedCharge::create(
                        [
                            "structure_detail_id"=>$new_structure_detail->id,
                            "description"=>$fixed_charge['description'],
                            "value"=>$fixed_charge['value']
                        ]
                    );
                    //Fixed subsidies
                    if(array_key_exists('subsidies', $fixed_charge)) {
                        foreach($fixed_charge['subsidies'] as $fixed_subsidy){
                            Subsidy::create(
                                [
                                    "structure_detail_id"=>$new_structure_detail->id,
                                    "type"=>"fixed",
                                    "charge_id"=>$new_fixed_charge->id,
                                    "description"=>$fixed_subsidy['description'],
                                    "value"=>$fixed_subsidy['value']
                                ]
                            );
                        }
                    };
                }
                //Energy Charges
                foreach($subcategory['energy_charges'] as $energy_charge) {
                    $json_energy_price=array_filter($data["energy_prices"], function($price) use ($energy_charge) {
                                                return $energy_charge['energy_price_json_id']=== $price["json_id"];
                                            }
                                        ); //este filtro devuelve un array de este formato: array:1 [indice del elemento que pasó el filtro => array:3 [ ... ]]
                    //Es como un array de arrays, solo que tiene un elemento. Entonces utilizo la funcion reset que toma el primer elemento de un array
                    $json_energy_price=reset($json_energy_price); 
                    if (array_key_exists('ape_charge_id', $energy_charge)) {
                        $db_ape_charge=APECharge::find($energy_charge['ape_charge_id']);
                        $new_energy_charge=EnergyCharge::create(
                            [
                                "structure_detail_id"=> $new_structure_detail->id,
                                "energy_price_id"=> $json_energy_price['db_id'],
                                "ape_charge_id"=> $db_ape_charge->id,
                                "description"=> $energy_charge['description'],
                                "min_range"=> $energy_charge['min_range'],
                                "max_range"=> $energy_charge['max_range'],
                                "value"=> (($json_energy_price['value']/(1-(3.5/100))+$db_ape_charge->value))/(1-($energy_charge['energy_loss_percentage']/100))
                            ]
                        );
                    } else {
                        $new_energy_charge=EnergyCharge::create(
                            [
                                "structure_detail_id"=> $new_structure_detail->id,
                                "energy_price_id"=> $json_energy_price['db_id'],
                                "ape_charge_id"=> $new_ape_charge->id,
                                "description"=> $energy_charge['description'],
                                "min_range"=> $energy_charge['min_range'],
                                "max_range"=> $energy_charge['max_range'],
                                "value"=> (($json_energy_price['value']/(1-(3.5/100))+ $new_ape_charge->value))/(1-($energy_charge['energy_loss_percentage']/100))
                            ]
                        ); 
                    }
                    //Energy subsidies
                    if(array_key_exists('subsidies', $energy_charge)) {
                        foreach($energy_charge['subsidies'] as $energy_subsidy){
                            Subsidy::create(
                                [
                                    "structure_detail_id"=>$new_structure_detail->id,
                                    "type"=>"energy",
                                    "charge_id"=>$new_energy_charge->id,
                                    "description"=>$energy_subsidy['description'],
                                    "value"=>$energy_subsidy['value']
                                ]
                            );
                        }
                    }        
                }
                //Step Charges
                foreach($subcategory['step_charges'] as $step_charge) {
                    $new_step_charge=StepCharge::create(
                        [
                            "structure_detail_id"=>$new_structure_detail->id,
                            "description"=> $step_charge['description'],
                            "unit"=>$step_charge["unit"],
                            "min_range"=>$step_charge["min_range"],
                            "max_range"=>$step_charge["max_range"],
                            "value"=>$step_charge["value"],
                        ]
                    );
                    //Step subsidies
                    if(array_key_exists('subsidies', $step_charge)) {
                        foreach($step_charge['subsidies'] as $step_subsidy){
                            Subsidy::create(
                                [
                                    "structure_detail_id"=>$new_structure_detail->id,
                                    "type"=>"step",
                                    "charge_id"=>$new_step_charge->id,
                                    "description"=>$step_subsidy['description'],
                                    "value"=>$step_subsidy['value']
                                ]
                            );
                        }
                    }
                }
                //Energy Injection Charge
                foreach($subcategory['energy_injection_charges'] as $energy_injection_charge) {
                    $new_injection_charge=EnergyInjectionCharge::create(
                        [
                            "structure_detail_id"=> $new_structure_detail->id,
                            "description"=> $energy_injection_charge['description'],
                            "value"=>$energy_injection_charge['value'],
                        ]
                    );
                }
                //Consumptions
                foreach($subcategory['consumptions'] as $consumption) {
                    $new_consumption=Consumption::create(
                        [
                            "structure_detail_id"=> $new_structure_detail->id, 
                            "kwh_value"=>$consumption['kwh_value'], 
                            "kvarh_value"=> $consumption['kvarh_value'],
                            "kw_value"=>$consumption['kw_value']
                        ]
                    );
                    //Injections
                    if(array_key_exists('injection', $consumption)) {
                        Injection::create(
                            [
                                "consumption_id"=>$new_consumption->id, 
                                "kwh_value"=>$consumption['injection']['kwh_value']
                            ]
                        );                        
                    }
                }
            }
        }
        //Cargo todas las relaciones a la nueva estructura 
        $new_structure->load(
            'structure_details.subcategory.category',
            'structure_details.fixed_charges',
            'structure_details.energy_charges.ape_charge',
            'structure_details.energy_charges.energy_price',
            'structure_details.step_charges',
            'structure_details.subsidies',
            'structure_details.energy_injection_charges',
            'structure_details.consumptions.injection',
        );

        return $new_structure;
    }

    public function generate_JSON (array $last_structure, array $data, string $periodo_structure, string $year_structure): array {

        $new_structure_array=[];

        //Datos de Cabecera
        $new_structure_array['period_id']=$data['period_id'];
        $new_structure_array['year_id']=$data['year_id'];

        //Detalles del Cuadro Consolidado 
        $detalles_last_structure=$last_structure['structure_details'];

        //VAD DE APE DE ULTIMO CUADRO TARIFARIO CONSOLIDADO
        $vad_ape_last_structure=(float) APEcharge::where('description','like',"%".$periodo_structure. " ".$year_structure."%")->first()->value;
        

        //Variables de cambios, filtros 
        //APE
        $categorias_exceptuadas_incremento_VAD_APE=[];
        //Fixed Charges
        $fixed_charges_changes=[];
        $categorias_exceptuadas_fixed_charges_changes=[];
        //Step Charges
        $step_charges_changes=[];  
        $categorias_exceptuadas_step_charges_changes=[];
        //Subsidy Charges
        $subsidy_charges_changes=[];  
        $categorias_exceptuadas_subsidy_charges_changes=[];

        $hay_removal = false;
        $hay_addition = false;
        $hay_cambio_monomicos=false;

        
        //VAD DE APE DE LA NUEVA ESTRUCTURA EN CASO DE QUE NO HAYA INCREMENTO DE VAD DE APE
        $new_structure_array['ape_charge']['value']=$vad_ape_last_structure;


        foreach ($data['changes'] as $index=>$change) {
            //Incremento constante o porcentual
            if (in_array($change['type'],['increase_%','increase_C'])){
                //VAD APE
                if($change['type_charge'] === 'APE') {      
                    if($change['type']==='increase_%') {
                        $new_structure_array['ape_charge']['value']= $vad_ape_last_structure*(1+($change['value']/100));
                    } else {
                        $new_structure_array['ape_charge']['value']= $vad_ape_last_structure+$change['value'];
                    }
                    foreach($change['except_to'] as $clave) {
                        $categorias_encontradas=Category::where('description','like',"%".$clave."%")->get();
                        foreach($categorias_encontradas as $categoria_encontrada) {
                            $categorias_exceptuadas_incremento_VAD_APE[]=$categoria_encontrada->id;
                        }
                    }
                }
                //FIXED
                if($change['type_charge'] === 'fixed') {
                    $fixed_charges_changes[$index][]=[$change['type'],$change['value'],$change['filter']];
                    foreach($change['except_to'] as $clave) {
                        $categorias_encontradas=Category::where('description','like',"%".$clave."%")->get();
                        foreach($categorias_encontradas as $categoria_encontrada) {
                            $categorias_exceptuadas_fixed_charges_changes[$index][]=$categoria_encontrada->id;                   
                        }
                    }
                }
                //STEP
                if($change['type_charge'] === 'step') {
                    $step_charges_changes[$index][]=[$change['type'],$change['value'],$change['filter']];
                    foreach($change['except_to'] as $clave) {
                        $categorias_encontradas=Category::where('description','like',"%".$clave."%")->get();
                        foreach($categorias_encontradas as $categoria_encontrada) {
                            $categorias_exceptuadas_step_charges_changes[$index][]=$categoria_encontrada->id;  
                        }
                    }
                }
                //SUBSIDY
                if($change['type_charge'] === 'subsidy') {
                    $subsidy_charges_changes[$index][]=[$change['type'],$change['value'],$change['filter']];
                    foreach($change['except_to'] as $clave) {
                        $categorias_encontradas=Category::where('description','like',"%".$clave."%")->get();
                        foreach($categorias_encontradas as $categoria_encontrada) {
                            $categorias_exceptuadas_subsidy_charges_changes[$index][]=$categoria_encontrada->id;  
                        }
                    }
                }      
            }
        }

        dd($fixed_charges_changes,$categorias_exceptuadas_fixed_charges_changes);


        //$new_structure_JSON=json_encode($new_structure_array, JSON_PRETTY_PRINT);
        
    }

    //Función que convierte estructura a JSON
    public function convert_JSON (array $structure): array {
        $array=[];
        dd("holasdasdasdsaa");
    }  

}