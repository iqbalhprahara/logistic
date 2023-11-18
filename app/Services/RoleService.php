<?php

namespace App\Services;

use App\Dto\Role\CreateRoleDto;
use App\Dto\Role\UpdateRoleDto;
use Spatie\Permission\Models\Role;

final class RoleService
{
    public function createRole(CreateRoleDto $createRoleDto): Role
    {
        $role = new Role();
        $role->fill([
            'name' => $createRoleDto->name,
            'guard' => 'web',
        ]);
        $role->save();
        $role->syncPermissions($createRoleDto->permissions);

        return $role;
    }

    public function updateRole(UpdateRoleDto $updateRoleDto): Role
    {
        $role = Role::find($updateRoleDto->id);
        $role->fill([
            'name' => $updateRoleDto->name,
        ]);
        $role->save();
        $role->syncPermissions($updateRoleDto->permissions);

        return $role;
    }
}
