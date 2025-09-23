<?php
namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Period;
use App\Models\Category;
use App\Models\Subcategory;

class ShowChangeAction {

    public function show(array $data): array {
        //Variable de Salida
        $change_value=[];
        //Año de entrada
        $year_entry=substr($data['id'], -4);
        //Periodo de entrada
        $period_entry = substr($data['id'], 0, -4);

        // Obtener todos los archivos de la carpeta storage/app
        $files = Storage::files('');
        //Filtro por aquellos que son .json y que sean distintos a la estructura de referencia del sistema
        $json_files = collect($files)
                    ->filter(fn($file) => str_ends_with($file, 'structure.json') && basename($file) !== '6_2025_structure.json')
                    ->map(fn($file) => basename($file))
                    ->values()
                    ->toArray();

        //Verifico si la proyección existe
        if (!in_array("{$period_entry}_{$year_entry}_structure.json", $json_files)) {
        throw ValidationException::withMessages([
            'id' => ["La proyección JSON correspondiente al periodo {$period_entry} y año {$year_entry} no existe."],
        ]);
        }

        //Obtengo su contenido
        $change_path = storage_path("app/{$period_entry}_{$year_entry}_structure.json"); //Recurro a la ruta del cambio
        $change_content = file_get_contents($change_path); //Obtengo cadena JSON
        $change_data = json_decode($change_content, true); //Obtengo un array asociativo de la primera estructura del sistema

        //Construyo el array de salida
        $change_value['id']=$data['id'];
        $period_description=Period::find($period_entry)->description;
        $change_value['description']="Proyección $period_description $year_entry";
        $change_value['structure_details']=[];

        foreach($change_data['categories'] as $category) {
            foreach($category['subcategories'] as $subcategory){
                $change_value['structure_details'][]=[
                    'subcategory_id'=>$subcategory['id'],
                    'subcategory'=> [
                        "id"=>$subcategory['id'],
                        "category_id"=> $category['id'],
                        "description"=> Subcategory::find($subcategory['id'])->description,
                        "category"=>[
                            'id'=>$category['id'],
                            'description'=>Category::find($category['id'])->description
                        ]
                    ]
                ];    
            }
        }

        return $change_value;

    }
}