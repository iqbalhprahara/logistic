<?php

namespace Database\Seeders;

use Core\MasterData\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            Service::updateOrCreate(
                ['id' => $data['id']],
                ['description' => $data['description']],
            );
        }
    }

    private function data(): array
    {
        return [
            [
                'id' => 'REG',
                'description' => 'Regular',
            ],
            [
                'id' => 'EKONOMI',
                'description' => 'Cargo',
            ],
            [
                'id' => 'NDS',
                'description' => 'Next Day',
            ],
        ];
    }
}
