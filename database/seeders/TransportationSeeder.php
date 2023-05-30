<?php

namespace Database\Seeders;

use Core\MasterData\Models\Transportation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            Transportation::firstOrCreate(['id' => $data['id']], ['name' => $data['name']]);
        }
    }

    private function data(): array
    {
        return [
            [
                'id' => 'MOTORCYCLE',
                'name' => 'Motorcycle',
            ],
            [
                'id' => 'VAN',
                'name' => 'Van/Truck',
            ],
        ];
    }
}
