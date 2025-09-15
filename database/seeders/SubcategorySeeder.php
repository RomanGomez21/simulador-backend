<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subcategory;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories=[
            //Residenciales N1
            ['category_id'=> 1, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 1, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 1, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 1, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 1, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 1, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //Residenciales N2
            ['category_id'=> 2, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 350 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 350 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 2, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //Residenciales N3
            ['category_id'=> 3, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 250 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 250 kWh/mes y menores o iguales a 400 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 400 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 3, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //Alumbrado Público
            ['category_id'=> 4, 'description'=> 'Alumbrado Público'],
            //Comercial
            ['category_id'=> 5, 'description'=> 'Consumos menores o iguales a 60 kWh/mes'],
            ['category_id'=> 5, 'description'=> 'Consumos mayores a 60 kWh/mes y menores a 2000 kWh/mes'],
            ['category_id'=> 5, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes y menores o iguales a 4000 kWh/mes'],
            ['category_id'=> 5, 'description'=> 'Consumos mayores a 4000 kWh/mes'],
            //Industriales
            ['category_id'=> 6, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 6, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes y menores o iguales a 4000 kWh/mes'],
            ['category_id'=> 6, 'description'=> 'Consumos mayores a 4000 kWh/mes'],
            //Asociaciones Civiles
            ['category_id'=> 7, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 7, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //Oficiales
            ['category_id'=> 8, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 8, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //Entidades de Bien Público
            ['category_id'=> 9, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 350 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 350 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 9, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //Entidad Integrante del Sistema Nacional de Bomberos Voluntarios
            ['category_id'=> 10, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 10, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //Rurales
            ['category_id'=> 11, 'description'=> 'Consumos menores o iguales a 200 kWh/mes con transformador de 5 kVA'],
            ['category_id'=> 11, 'description'=> 'Consumos mayores a 200 kWh/mes y menores a 2000 kWh/mes con transformador de 5 kVA'],
            ['category_id'=> 11, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes con transformador de 5 kVA'],
            ['category_id'=> 11, 'description'=> 'Consumos menores o iguales a 200 kWh/mes con transformador de 10 kVA'],
            ['category_id'=> 11, 'description'=> 'Consumos mayores a 200 kWh/mes con transformador de 10 kVA'],
            ['category_id'=> 11, 'description'=> 'Consumos menores o iguales a 200 kWh/mes con transformador mayor a 10 kVA'], //Desarrollar lógica de facturación diferente al resto
            ['category_id'=> 11, 'description'=> 'Consumos mayores a 200 kWh/mes con transformador mayor a 10 kVA'], //Desarrollar lógica de facturación diferente al resto
            //Grandes Usuarios en BT 
            ['category_id'=> 12, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 12, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW'],
            ['category_id'=> 12, 'description'=> 'Potencias contratadas mayores o iguales a 3000 kW'],
            ['category_id'=> 12, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //Grandes Usuarios en 13,2 kV
            ['category_id'=> 13, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 13, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW'],
            ['category_id'=> 13, 'description'=> 'Potencias contratadas mayores o iguales a 3000 kW'],
            ['category_id'=> 13, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //Grandes Usuarios en 33 kV
            ['category_id'=> 14, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 14, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW'],
            ['category_id'=> 14, 'description'=> 'Potencias contratadas mayores o iguales a 3000 kW'],
            ['category_id'=> 14, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //Riego Agrícola
            ['category_id'=> 15, 'description'=> 'Potencias menores a 300 kW'],
            ['category_id'=> 15, 'description'=> 'Potencias mayores o iguales a 300 kW'],
            //ZNC: Residenciales N1
            ['category_id'=> 16, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 16, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 16, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 16, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 16, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 16, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //ZNC: Residenciales N2
            ['category_id'=> 17, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 350 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 350 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 17, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //ZNC: Residenciales N3
            ['category_id'=> 18, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 250 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 250 kWh/mes y menores o iguales a 400 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 400 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 18, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //ZNC: Alumbrado Público
            ['category_id'=> 19, 'description'=> 'Alumbrado Público'],
            //ZNC: Comerciales
            ['category_id'=> 20, 'description'=> 'Consumos menores o iguales a 60 kWh/mes'],
            ['category_id'=> 20, 'description'=> 'Consumos mayores a 60 kWh/mes y menores a 2000 kWh/mes'],
            ['category_id'=> 20, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes y menores o iguales a 4000 kWh/mes'],
            ['category_id'=> 20, 'description'=> 'Consumos mayores a 4000 kWh/mes'],
            //ZNC: Industriales
            ['category_id'=> 21, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 21, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes y menores o iguales a 4000 kWh/mes'],
            ['category_id'=> 21, 'description'=> 'Consumos mayores a 4000 kWh/mes'],
            //ZNC: Asociaciones Civiles
            ['category_id'=> 22, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 22, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //ZNC: Oficiales
            ['category_id'=> 23, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 23, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //ZNC: Entidades de Bien Público
            ['category_id'=> 24, 'description'=> 'Consumos menores o iguales a 120 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 120 kWh/mes y menores o iguales a 220 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 220 kWh/mes y menores o iguales a 350 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 350 kWh/mes y menores o iguales a 500 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 24, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //ZNC: Entidad Integrante del Sistema Nacional de Bomberos Voluntarios
            ['category_id'=> 25, 'description'=> 'Consumos menores a 2000 kWh/mes'],
            ['category_id'=> 25, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes'],
            //ZNC: Rurales
            ['category_id'=> 26, 'description'=> 'Consumos menores a 2000 kWh/mes con transformador de 5 kVA'],
            ['category_id'=> 26, 'description'=> 'Consumos mayores o iguales a 2000 kWh/mes con transformador de 5 kVA'],
            ['category_id'=> 26, 'description'=> 'Transformador de 10 kVA'],
            ['category_id'=> 26, 'description'=> 'Transformador mayor a 10 kVA'],
            //ZNC: Grandes Usuarios en BT
            ['category_id'=> 27, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 27, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW'],
            ['category_id'=> 27, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //ZNC: Grandes Usuarios en 13,2 kV
            ['category_id'=> 28, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 28, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW'],
            ['category_id'=> 28, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //ZNC: Grandes Usuarios en 33 kV
            ['category_id'=> 29, 'description'=> 'Potencias contratadas mayores a 50 kW y menores a 300 kW'],
            ['category_id'=> 29, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW'],
            ['category_id'=> 29, 'description'=> 'Potencias contratadas mayores o iguales a 300 kW y menores a 3000 kW (OP-SALUD-EDUC)'],
            //ZNC: Riego Agrícola
            ['category_id'=> 30, 'description'=> 'Potencias menores a 300 kW'],
            ['category_id'=> 30, 'description'=> 'Potencias mayores o iguales a 300 kW'],
            ['category_id'=> 30, 'description'=> 'Rural disperso'],
            //Electrodependientes
            ['category_id'=> 31, 'description'=> 'Consumos menores o iguales a 500 kWh/mes'],
            ['category_id'=> 31, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 31, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 31, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
            //ZNC: Electrodependientes 
            ['category_id'=> 32, 'description'=> 'Consumos menores o iguales a 500 kWh/mes'],
            ['category_id'=> 32, 'description'=> 'Consumos mayores a 500 kWh/mes y menores o iguales a 700 kWh/mes'],
            ['category_id'=> 32, 'description'=> 'Consumos mayores a 700 kWh/mes y menores o iguales a 1400 kWh/mes'],
            ['category_id'=> 32, 'description'=> 'Consumos mayores a 1400 kWh/mes'],
        ];

        foreach($subcategories as $row){
            Subcategory::firstOrCreate($row);
        }
    }
}
