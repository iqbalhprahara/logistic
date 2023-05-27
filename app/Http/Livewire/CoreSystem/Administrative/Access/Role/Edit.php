<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseComponent;
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

        $oldName = $this->role->getOriginal('name');
        $this->role->save();

        $this->emit('message', 'Role '.$oldName.' successfully updated to '.$this->role->name);
        $this->emit('close-modal', '#modal-edit-role-'.$this->role->id);
        $this->emit('refresh-table');
    }
}
