<?php

namespace Database\Seeders;

use Core\MasterData\Models\Packing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            Packing::updateOrCreate(
                ['id' => $data['id']],
                ['name' => $data['name']],
            );
        }
    }

    private function data(): array
    {
        return [
            [
                'id' => 'BUBBLE',
                'name' => 'Bubble Wrap',
            ],
            [
                'id' => 'CARDBOARD',
                'name' => 'Kardus',
            ],
            [
                'id' => 'WOOD',
                'name' => 'Kayu',
            ],
        ];
    }
}
