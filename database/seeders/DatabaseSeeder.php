<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Telescope\Telescope;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        Telescope::stopRecording();
        $this->call([
            MenuSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
            TransportationSeeder::class,
            ServiceSeeder::class,
            ShipmentTypeSeeder::class,
            PackingSeeder::class,
            LocationSeeder::class,
        ]);
        Telescope::startRecording();
    }
}
