<?php

namespace Database\Seeders;

use App\Imports\MasterData\LocationImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $filePath = 'location-seeder.xlsx';
        LocationImport::fromExcel($filePath)->import();
    }
}
