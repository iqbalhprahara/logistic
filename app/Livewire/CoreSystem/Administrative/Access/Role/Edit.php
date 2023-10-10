<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Role;

use App\Livewire\BaseComponent;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class Edit extends BaseComponent
{
    protected $gates = ['administrative:access:role:edit'];

    public $role;

    public function mount(Role $role)
    {
        $this->role = $role;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.role.edit');
    }

    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'web')
                    ->ignore($this->role->id),
            ],
        ];
    }

    public function update()
    {
        $this->authorize('update', $this->role);
        $this->validate($this->rules());

        $this->role->save();

        $this->dispatch('message', message: 'Role data successfully updated');
        $this->dispatch('close-modal', modalId:  '#modal-edit-role-'.$this->role->id);
        $this->dispatch('refresh-table');
    }
}
