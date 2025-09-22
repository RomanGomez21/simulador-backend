<?php
namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use App\Models\Period;

class IndexChangeAction {

    public function index(): array {

        // Obtener todos los archivos de la carpeta storage/app
        $files = Storage::files('');
        //Filtro por aquellos que son .json y que sean distintos a la estructura de referencia del sistema
        $json_files = collect($files)
                    ->filter(fn($file) => str_ends_with($file, 'structure.json') && basename($file) !== '6_2025_structure.json')
                    ->map(fn($file) => basename($file))
                    ->values()
                    ->toArray();

        // Agregar 'id' a cada elemento según regla de números concatenados. Ejemplo 7_2025_structure.json tendá un id=72025
        $json_files_with_ids = array_map(function($filename) {
        $base = str_replace('_structure.json', '', $filename);
        $parts = explode('_', $base);
        $period= Period::find($parts[0])->description;
        $id = implode('', $parts);

        return [
            'id' => (int) $id,
            'description' => "Proyección $period " . $parts[1],
        ];
         }, $json_files);

        return $json_files_with_ids;
    }
}