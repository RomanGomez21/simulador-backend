<?php
namespace App\Actions;

use App\Models\Structure;

class ShowStructureAction {

    public function show(array $data): array {

        return Structure::with('structure_details.subcategory.category')->findOrFail($data['id'])->toArray();
    }
}