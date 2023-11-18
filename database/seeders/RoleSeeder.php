<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedWebRole();
    }

    private function seedWebRole()
    {
        DB::transaction(function () {
            foreach (config('role.required.web') as $roleData) {
                /** @var Role $role */
                $role = Role::firstOrCreate(
                    [
                        'name' => $roleData['name'],
                        'guard_name' => 'web',
                    ],
                );

                if (is_array($roleData['permissions'])) {
                    $role->syncPermissions($roleData['permissions']);
                }
            }
        });
    }
}
