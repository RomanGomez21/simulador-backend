<?php
namespace App\Actions;

use App\Models\StructureDetail;

class ShowStructureDetailAction {
    
    public function show (array $data): array {
        
        return StructureDetail::with('subcategory.category')->findOrFail($data['id'])->toArray();

    }
}