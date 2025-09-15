<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(range(2000,2100) as $year) {
            Year::firstOrCreate(['value'=>(string) $year]);
        }
    }
}
