<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods=[
            ['description'=>'Enero'],
            ['description'=>'Febrero'],
            ['description'=>'Marzo'],
            ['description'=>'Abril'],
            ['description'=>'Mayo'],
            ['description'=>'Junio'],
            ['description'=>'Julio'],
            ['description'=>'Agosto'],
            ['description'=>'Septiembre'],
            ['description'=>'Octubre'],
            ['description'=>'Noviembre'],
            ['description'=>'Diciembre']
        ];

        foreach($periods as $row){
            Period::firstOrCreate($row);
        }
    }
}
