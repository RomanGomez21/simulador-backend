<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\APECharge;

class APEChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $APEcharges=[
            ["description"=>"VAD de APE Julio 2024" , "value"=> 8.28563 ]
        ];

        foreach($APEcharges as $row) {
            APECharge::firstOrCreate($row);
        }
    }
}
