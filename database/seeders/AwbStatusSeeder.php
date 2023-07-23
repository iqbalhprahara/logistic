<?php

namespace Database\Seeders;

use Core\MasterData\Models\AwbStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AwbStatusSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            AwbStatus::updateOrCreate(
                ['id' => $data['id']],
                ['name' => $data['name']],
            );
        }
    }

    protected function data(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Waiting for Pick up',
            ],
            [
                'id' => 2,
                'name' => 'Picked up',
            ],
            [
                'id' => 3,
                'name' => 'On process to branch',
            ],
            [
                'id' => 4,
                'name' => 'On process delivery',
            ],            [
                'id' => 5,
                'name' => 'Delivered',
            ],
            [
                'id' => 6,
                'name' => 'Undelivered',
            ],
            [
                'id' => 7,
                'name' => 'Return to shipper',
            ],
        ];
    }
}
