<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedWebPermissions();
    }

    private function seedWebPermissions()
    {
        $webAppPermissions = collect($this->buildPermissionsFromMenu(config('core.auth-menu.items.web'), 'web'))->unique(['name']);
        Permission::upsert($webAppPermissions->toArray(), ['name', 'guard_name']);
    }

    private function buildPermissionsFromMenu($menus, $guardName = 'web', array $permissions = [])
    {
        foreach ($menus as $menu) {
            if (isset($menu['gate'])) {
                $permissions[] = [
                    'name' => $menu['gate'],
                    'guard_name' => $guardName,
                ];
            }

            if (isset($menu['permissions']) && is_array($menu['permissions'])) {
                foreach ($menu['permissions'] as $permission) {
                    if (isset($permission['gate'])) {
                        $permissions[] = [
                            'name' => $permission['gate'],
                            'guard_name' => $guardName,
                        ];
                    }
                }
            }

            if (isset($menu['submenus']) && is_array($menu['submenus'])) {
                $permissions = $this->buildPermissionsFromMenu($menu['submenus'], $guardName, $permissions);
            }
        }

        return $permissions;
    }
}
