<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseComponent;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class Create extends BaseComponent
{
    protected $gates = ['administrative:access:role:create'];

    public $role;

    public function mount()
    {
        $this->initializeRole();
    }

    public function initializeRole()
    {
        $this->role = new Role;
        $this->role->guard_name = 'web';
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.role.create');
    }

    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'web'),
            ],
        ];
    }

    public function store()
    {
        $this->authorize('create', Role::class);
        $this->validate($this->rules());

        $this->role->save();

        $name = $this->role->name;

        $this->initializeRole();

        $this->emit('message', 'Role '.$name.' successfully created');
        $this->emit('close-modal', '#modal-create-role');
        $this->emit('refresh-table');
    }
}
