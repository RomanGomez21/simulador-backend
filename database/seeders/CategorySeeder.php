<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=[
            //Cooperativas (ANEXO I)
            ["description"=>"Residencial N1"],
            ["description"=>"Residencial N2"],
            ["description"=>"Residencial N3"],
            ["description"=>"Alumbrado Público"],
            ["description"=>"Comerciales"],
            ["description"=>"Industriales"],
            ["description"=>"Asociaciones Civiles"],
            ["description"=>"Oficiales"],
            ["description"=>"Entidades de Bien Público"],
            ["description"=>"Entidad Integrante del Sistema Nacional de Bomberos Voluntarios"],
            ["description"=>"Rurales"],
            ["description"=>"Grandes Usuarios en BT"],
            ["description"=>"Grandes Usuarios en 13,2 kV"],
            ["description"=>"Grandes Usuarios en 33 kV"],
            ["description"=>"Riego Agrícola"],
            //ZNC (ANEXO II)
            ["description"=>"Residencial N1 ZNC"],
            ["description"=>"Residencial N2 ZNC"],
            ["description"=>"Residencial N3 ZNC"],
            ["description"=>"Alumbrado Público ZNC"],
            ["description"=>"Comerciales ZNC"],
            ["description"=>"Industriales ZNC"],
            ["description"=>"Asociaciones Civiles ZNC"],
            ["description"=>"Oficiales ZNC"],
            ["description"=>"Entidades de Bien Público ZNC"],
            ["description"=>"Entidad Integrante del Sistema Nacional de Bomberos Voluntarios ZNC"],
            ["description"=>"Rurales ZNC"],
            ["description"=>"Grandes Usuarios en BT ZNC"],
            ["description"=>"Grandes Usuarios en 13,2 kV ZNC"],
            ["description"=>"Grandes Usuarios en 33 kV ZNC"],
            ["description"=>"Riego Agrícola ZNC"],
            //ELECTRODEPENDIENTES COOPERATIVAS (ANEXO III)
            ["description"=>"Electrodependientes"],
            //ELECTRODEPENDIENTES ZNC (ANEXO IV)
            ["description"=>"Electrodependientes ZNC"],
        ];

        foreach($categories as $row) {
            Category::firstOrCreate($row);
        }
    }
}
