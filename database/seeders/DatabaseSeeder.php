<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        if (class_exists("\Laravel\Telescope\Telescope")) {
            \Laravel\Telescope\Telescope::stopRecording();
        }

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
            TransportationSeeder::class,
            ServiceSeeder::class,
            ShipmentTypeSeeder::class,
            PackingSeeder::class,
            // LocationSeeder::class,
            AwbStatusSeeder::class,
        ]);
    }
}
