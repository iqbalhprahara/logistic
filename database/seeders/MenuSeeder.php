<?php

namespace Database\Seeders;

use Core\Auth\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedWebMenus();
    }

    private function seedWebMenus()
    {
        DB::transaction(function () {
            foreach (config('core.auth-menu.items.web') as $menu) {
                $this->createMenu($menu);
            }
        });
    }

    private function createMenu($menuData, $guardName = 'web', null|Menu $parent = null)
    {
        $menu = Menu::updateOrCreate(
            [
                'id' => $menuData['id'],
            ],
            [
                'name' => $menuData['name'],
                'description' => optional($menuData)['description'],
                'icon' => optional($menuData)['icon'],
                'type' => optional($menuData)['type'],
                'url' => optional($menuData)['url'],
                'gate' => optional($menuData)['gate'],
                'sort' => optional($menuData)['sort'],
                'guard_name' => $guardName,
                'parent_uuid' => optional($parent)->uuid,
            ],
        );

        if (isset($menuData['submenus'])) {
            foreach ($menuData['submenus'] as $submenu) {
                $this->createMenu($submenu, $guardName, $menu);
            }
        }
    }
}
