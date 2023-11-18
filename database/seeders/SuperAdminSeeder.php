<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if (config('app.env') !== 'production') {
            $superAdmin = User::updateOrcreate(
                [
                    'email' => 'admin@banana-xpress.com',
                ],
                [
                    'name' => 'Administrator',
                    'email_verified_at' => now(),
                    'password' => '$2y$10$C3xpji8DggFiK0nvSIOEKe5Y5iUcJlMXffRyOD6NFfmeAZX4XEhSO',
                ]
            );
            $superAdmin->assignRole('Super Admin');
        }
    }
}
