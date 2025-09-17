<?php
namespace App\Actions;

use App\Models\Structure;

class IndexStructureAction {
    
    public function index() {
        
        $structure=Structure::with('structure_details.subcategory.category',
                                'structure_details.fixed_charges',
                                'structure_details.energy_charges.ape_charge',
                                'structure_details.energy_charges.energy_price',
                                'structure_details.step_charges',
                                'structure_details.subsidies',
                                'structure_details.energy_injection_charges',
                                'structure_details.consumptions.injection',)
                            ->get();
        
        return $structure;       
    }
}