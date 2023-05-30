<?php

namespace Core\Auth\Policies;

use Core\Auth\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return Gate::allows('administrative:access:role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return Gate::allows('administrative:access:role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Gate::allows('administrative:access:role:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return Gate::allows('administrative:access:role:update') && ! in_array($role->getOriginal('name'), ['Super Admin', 'Client']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return Gate::allows('administrative:access:role:delete') && ! in_array($role->name, ['Super Admin', 'Client']);
    }

    public function assignPermissions(User $user, Role $role): bool
    {
        return Gate::allows('administrative:access:role:assign-permissions') && ! in_array($role->name, ['Super Admin', 'Client']);
    }
}
