<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StructureService;

class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VOLCAR ESTRUCTURA DE REFERENCIA PARA DAR INICIO AL SISTEMA';

    /**
     * Execute the console command.
     */

    protected $structureService;

    public function __construct(StructureService $structure_service)
        {
            parent::__construct();
            $this->structureService = $structure_service;
        }

    public function handle()
    {
        $first_structure_path = storage_path('app\6_2025_structure.json'); //Recurro a la ruta de la primera estructura del sistema
        $first_structure_content = file_get_contents($first_structure_path); //Obtengo cadena JSON
        $first_structure_data = json_decode($first_structure_content, true); //Obtengo un array asociativo de la primera estructura del sistema
        
        $result=$this->structureService->store($first_structure_data);

        $this->info("Sistema de proyección de tarifa de La Pampa iniciado correctamente");
        return 0;
    }
}
