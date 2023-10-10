<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Admin;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Edit extends BaseComponent
{
    protected $gates = ['administrative:access:admin:edit'];

    /** @var User */
    public $admin;

    public $role;

    public Collection $roleList;

    public function mount(User $admin, $roleList)
    {
        $this->admin = $admin;
        $this->role = optional($admin->roles->first())->id;
        $this->roleList = $roleList;
    }

    public function updatedRole($value)
    {
        $this->role = intval($value);
    }

    public function hydrate()
    {
        $this->dispatch('initSelectRoleEditAdmin', adminUuid: $this->admin->uuid)->self();
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.admin.edit');
    }

    protected function rules()
    {
        return [
            'admin.name' => [
                'required',
                'string',
            ],
            'admin.email' => [
                'required',
                'email:rfc,dns,spoof',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->admin->uuid, 'uuid'),
            ],
            'role' => [
                'required',
                Rule::exists('roles', 'id')
                    ->whereNot('name', 'Client'),
            ],
        ];
    }

    public function update()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $this->admin->save();
            $this->admin->syncRoles($this->role);
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->dispatch('message', message: 'Admin data successfully updated');
        $this->dispatch('close-modal', modalId:  '#modal-edit-admin-'.$this->admin->uuid);
        $this->dispatch('refresh-table');
    }
}
