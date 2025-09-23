<?php
namespace App\Services;

class CalculateService {

    public function calculate_average_consumption_from_T (array $structure_detail): array {
        $result=[];

        foreach($structure_detail['consumptions'] as $consumption) {
            $total=0;
            $consumo_kwh   = (float) $consumption['kwh_value'] ?? null;
            $consumo_kvarh = (float) $consumption['kvarh_value'] ?? null;
            $consumo_kw    = (float) $consumption['kw_value'] ?? null;
            if(isset($consumption['injection'])) {
                $injeccion_kwh= (float) $consumption['injection']['kwh_value'];
                $result['with_injection']=0;
            } else {
                $result['without_injection']=0;
            }

            //Parte de Cargos Fijos
            foreach($structure_detail['fixed_charges'] as $fixed_charge){
                $subsidios_del_cargo=array_filter(
                    $structure_detail['subsidies'], 
                    function ($sub) use ($fixed_charge) {
                        return $sub['charge_id'] === $fixed_charge['id'] && $sub['type'] === 'fixed';
                    }
                );

                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $fixed_charge['value']=(float) $fixed_charge['value'] + (float) $subsidio_del_cargo['value'];
                }

                $total=$total + (float) $fixed_charge['value'];   
                //Parte de Subsidios aplicados al Costo Fijo
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $total=$total - (float) $subsidio_del_cargo['value'];
                }
            }

            //Parte de Costos de Compras de Energía
            foreach($structure_detail['energy_charges'] as $energy_charge) {
                //Mínimo del rango 
                $min = (float) $energy_charge['min_range'];
                //Máximo del rango
                $max = $energy_charge['max_range'] !== null ? (float) $energy_charge['max_range'] : null;
                $subsidios_del_cargo=array_filter(
                    $structure_detail['subsidies'], 
                    function ($sub) use ($energy_charge) {
                        return $sub['charge_id'] === $energy_charge['id'] && $sub['type'] === 'energy';
                    }
                );
                if ($consumo_kwh <= $min) {
                    continue; // El consumo es menor al valor minimo del rango de energía del costo, entonces salto al siguiente elemento del foreach hasta que termine 
                }

                // kWh aplicables para este costo de energía
                $consumo_aplicable = $max !== null ? min($consumo_kwh, $max) - $min: $consumo_kwh - $min;

                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $energy_charge['value']=(float) $energy_charge['value'] + (float) $subsidio_del_cargo['value'];
                }
                $total= $total + (float) $consumo_aplicable*$energy_charge['value'];  
                //Parte de Subsidios aplicados al costo de compra de energía
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $total=$total -(float) $subsidio_del_cargo['value']*$consumo_aplicable;
                }
            }

            //Parte de Escalones de Consumo 
            foreach($structure_detail['step_charges'] as $step_charge) { 
                //Mínimo del rango 
                $min = (float) $step_charge['min_range'];
                //Máximo del rango
                $max = $step_charge['max_range'] !== null ? (float) $step_charge['max_range'] : null;
                $subsidios_del_cargo=array_filter(
                    $structure_detail['subsidies'], 
                    function ($sub) use ($step_charge) {
                        return $sub['charge_id'] === $step_charge['id'] && $sub['type'] === 'step';
                    }
                );
                if ($consumo_kwh <= $min) {
                    continue; // El consumo es menor al valor minimo del rango de energía del costo, entonces salto al siguiente elemento del foreach hasta que termine 
                }
                // kWh aplicables para el escalón de consumo
                $consumo_aplicable = $max !== null ? min($consumo_kwh, $max) - $min: $consumo_kwh - $min;
                
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $step_charge['value']=(float) $step_charge['value'] + (float) $subsidio_del_cargo['value'];
                }
                $total= $total + (float) $consumo_aplicable*$step_charge['value'];
                //Parte de Subsidios aplicados al escalón de consumo
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                     $total = $total -(float) $subsidio_del_cargo['value']*$consumo_aplicable;
                }
            }

            if(isset($consumption['injection'])) {
                    $tarifa_inyeccion=(float) $structure_detail['energy_injection_charges'][0]['value'];
                    $total= $total - (float) $tarifa_inyeccion*$injeccion_kwh;
                    $result['with_injection']=$total;
            } else {
                    $result['without_injection']=$total;
            }

        }
        
        return $result;
        
    }

    public function calculate_average_consumption_from_P (array $subcategory): array {
        $result=[];
        
        foreach($subcategory['consumptions'] as $consumption) {
            $total=0;
            $consumo_kwh   = (float) $consumption['kwh_value'] ?? null;
            $consumo_kvarh = (float) $consumption['kvarh_value'] ?? null;
            $consumo_kw    = (float) $consumption['kw_value'] ?? null;

            if(array_key_exists('injection',$consumption)) {
                $injeccion_kwh= (float) $consumption['injection']['kwh_value'];
                $result['with_injection']=0;
            } else {
                $result['without_injection']=0;
            }

             //Parte de Cargos Fijos
            foreach($subcategory['fixed_charges'] as $fixed_charge){
                foreach($fixed_charge['subsidies'] as $subsidio_del_cargo) {
                    $fixed_charge['value']=(float) $fixed_charge['value'] + (float) $subsidio_del_cargo['value'];
                }
                $total=$total + (float) $fixed_charge['value'];   
                //Parte de Subsidios aplicados al Costo Fijo
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $total=$total - (float) $subsidio_del_cargo['value'];
                }
            }

            //Parte de Costos de Compras de Energía
            foreach($structure_detail['energy_charges'] as $energy_charge) {
                //Mínimo del rango 
                $min = (float) $energy_charge['min_range'];
                //Máximo del rango
                $max = $energy_charge['max_range'] !== null ? (float) $energy_charge['max_range'] : null;
                
                $energy_charge_value=0;
                if ($consumo_kwh <= $min) {
                    continue; // El consumo es menor al valor minimo del rango de energía del costo, entonces salto al siguiente elemento del foreach hasta que termine 
                }

                // kWh aplicables para este costo de energía
                $consumo_aplicable = $max !== null ? min($consumo_kwh, $max) - $min: $consumo_kwh - $min;

                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $energy_charge['value']=(float) $energy_charge['value'] + (float) $subsidio_del_cargo['value'];
                }
                $total= $total + (float) $consumo_aplicable*$energy_charge['value'];  
                //Parte de Subsidios aplicados al costo de compra de energía
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $total=$total -(float) $subsidio_del_cargo['value']*$consumo_aplicable;
                }
            }

            //Parte de Escalones de Consumo 
            foreach($structure_detail['step_charges'] as $step_charge) { 
                //Mínimo del rango 
                $min = (float) $step_charge['min_range'];
                //Máximo del rango
                $max = $step_charge['max_range'] !== null ? (float) $step_charge['max_range'] : null;
                $subsidios_del_cargo=array_filter(
                    $structure_detail['subsidies'], 
                    function ($sub) use ($step_charge) {
                        return $sub['charge_id'] === $step_charge['id'] && $sub['type'] === 'step';
                    }
                );
                if ($consumo_kwh <= $min) {
                    continue; // El consumo es menor al valor minimo del rango de energía del costo, entonces salto al siguiente elemento del foreach hasta que termine 
                }
                // kWh aplicables para el escalón de consumo
                $consumo_aplicable = $max !== null ? min($consumo_kwh, $max) - $min: $consumo_kwh - $min;
                
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                    $step_charge['value']=(float) $step_charge['value'] + (float) $subsidio_del_cargo['value'];
                }
                $total= $total + (float) $consumo_aplicable*$step_charge['value'];
                //Parte de Subsidios aplicados al escalón de consumo
                foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                     $total = $total -(float) $subsidio_del_cargo['value']*$consumo_aplicable;
                }
            }

            if(isset($consumption['injection'])) {
                    $tarifa_inyeccion=(float) $structure_detail['energy_injection_charges'][0]['value'];
                    $total= $total - (float) $tarifa_inyeccion*$injeccion_kwh;
                    $result['with_injection']=$total;
            } else {
                    $result['without_injection']=$total;
            }

        }

        return($result);
    }
}