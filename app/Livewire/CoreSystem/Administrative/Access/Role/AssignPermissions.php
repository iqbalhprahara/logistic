<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Role;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissions extends BaseComponent
{
    protected $gates = ['administrative:access:role:assign-permissions'];

    /** @var Role */
    public $role;

    /** @var Permission */
    public $permissions;

    protected $menus;

    public function boot()
    {
        parent::boot();

        $this->menus = config('core.auth-menu.items.web');
    }

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->permissions = $role->permissions;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.role.assign_permissions', [
            'menus' => $this->menus,
        ]);
    }

    public function toggleAllPermissions()
    {
        $allPermissions = Permission::whereGuardName('web')->pluck('name')->toArray();
        $selectAll = false;
        if ($this->role->hasAllPermissions($allPermissions)) {
            $this->role->syncPermissions([]);
        } else {
            $this->role->syncPermissions($allPermissions);
            $selectAll = true;
        }

        app(MenuRegistry::class)->forgetCachedMenus();

        $text = $selectAll ? 'assigned' : 'revoked';
        $this->dispatch('message', message: 'Successfully '.$text.' all permissions for role '.$this->role->name);
    }

    public function togglePermission(string $permission)
    {
        if (! Permission::whereName($permission)->whereGuardName('web')->exists()) {
            return abort(404);
        }

        $assign = false;
        if ($this->role->hasPermissionTo($permission)) {
            $item = $this->getMenuItemByPermission($this->menus, $permission);
            $permissions = $this->getAllMenuPermissions($item);

            $this->role->revokePermissionTo($permissions);
        } else {
            $assign = true;
            $allParentPermissions = Arr::pluck(
                $this->getAllMenuParentByPermission($this->menus, $permission),
                'gate',
            );

            $this->role->givePermissionTo(array_merge([$permission], $allParentPermissions));
        }

        app(MenuRegistry::class)->forgetCachedMenus();

        $text = $assign ? 'assigned' : 'revoked';

        $this->dispatch('message', message: 'Successfully '.$text.' permission');
    }

    protected function getMenuItemByPermission($menus, string $permission)
    {
        if (empty($menus)) {
            return [];
        }

        foreach ($menus as $menu) {
            if (isset($menu['gate']) && $menu['gate'] === $permission) {
                return $menu;
            }

            if (isset($menu['submenus'])) {
                $menu = $this->getMenuItemByPermission($menu['submenus'], $permission);

                if ($menu) {
                    return $menu;
                }
            }

            if (isset($menu['permissions'])) {
                $menu = $this->getMenuItemByPermission($menu['permissions'], $permission);

                if ($menu) {
                    return $menu;
                }
            }
        }

        return [];
    }

    protected function getAllMenuPermissions($menu, $result = [])
    {
        if (isset($menu['gate'])) {
            $result[] = $menu['gate'];
        }

        if (isset($menu['submenus'])) {
            foreach ($menu['submenus'] as $submenu) {
                $result = $this->getAllMenuPermissions($submenu, $result);
            }
        }

        if (isset($menu['permissions'])) {
            foreach ($menu['permissions'] as $permission) {
                $result[] = $permission['gate'];
            }
        }

        return $result;
    }

    protected function getAllMenuParentByPermission($menus, string $permission, $result = [])
    {
        foreach ($menus as $menu) {
            if ($menu['gate'] === $permission) {
                $result[] = $menu;
            }

            if (isset($menu['submenus'])) {
                $resultSubMenu = $this->getAllMenuParentByPermission($menu['submenus'], $permission);

                if ($resultSubMenu) {
                    $result[] = $menu;
                    $result = array_merge($result, $resultSubMenu);
                }
            }

            if (isset($menu['permissions'])) {
                foreach ($menu['permissions'] as $menuPermission) {
                    if ($menuPermission['gate'] === $permission) {
                        $result[] = $menu;
                    }
                }
            }
        }

        return collect($result)->unique('id')->toArray();
    }
}
