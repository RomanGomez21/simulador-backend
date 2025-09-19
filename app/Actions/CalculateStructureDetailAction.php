<?php
namespace App\Actions;

use App\Models\StructureDetail;

class CalculateStructureDetailAction {

    public function execute(array $data): array {
        $result=[];
        $result['headers']=['Concepto','Unidad','P.U.','Cantidad','Monto [$]'];
        $structure_detail=StructureDetail::with(
            'fixed_charges',
            'energy_charges',
            'step_charges',
            'subsidies')->findOrFail($data['id'])->toArray();
        
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
            $result['detalle_calculado'][]=[$fixed_charge['description'],'$/mes',$fixed_charge['value'],1,$fixed_charge['value']];   
            //Parte de Subsidios aplicados al Costo Fijo
            foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                $result['detalle_calculado'][]=[$subsidio_del_cargo['description'],'$/mes',$subsidio_del_cargo['value'],1,-$subsidio_del_cargo['value']];
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
            if ($data['consumo_energia'] <= $min) {
                continue; // El consumo es menor al valor minimo del rango de energía del costo, entonces salto al siguiente elemento del foreach hasta que termine 
            }

            // kWh aplicables para este costo de energía
            $consumo_aplicable = $max !== null ? min($data['consumo_energia'], $max) - $min: $data['consumo_energia'] - $min;

            foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                $energy_charge['value']=(float) $energy_charge['value'] + (float) $subsidio_del_cargo['value'];
            }
            $result['detalle_calculado'][]=[$energy_charge['description'],'$/kWh',$energy_charge['value'],$consumo_aplicable,$energy_charge['value']*$consumo_aplicable];   
            //Parte de Subsidios aplicados al costo de compra de energía
            foreach($subsidios_del_cargo as $subsidio_del_cargo) {
                $result['detalle_calculado'][]=[$subsidio_del_cargo['description'],'$/kWh',$subsidio_del_cargo['value'],$consumo_aplicable,-$subsidio_del_cargo['value']*$consumo_aplicable];
            }
        }
        //Parte de Escalones de Consumo 
        foreach($structure_detail['step_charges'] as $step_charge) { 

        }

        dd($result);
    }
}