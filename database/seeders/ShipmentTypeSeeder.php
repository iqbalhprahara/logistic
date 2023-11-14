<?php

namespace Database\Seeders;

use App\Models\MasterData\ShipmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            ShipmentType::updateOrCreate(
                ['id' => $data['id']],
                ['name' => $data['name']],
            );
        }
    }

    private function data(): array
    {
        return [
            [
                'id' => 'PACKAGE',
                'name' => 'Paket',
            ],
            [
                'id' => 'DOC',
                'name' => 'Dokumen',
            ],
        ];
    }
}
