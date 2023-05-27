<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseComponent;
use Spatie\Permission\Models\Role;

class Delete extends BaseComponent
{
    protected $gates = ['administrative:access:role:delete'];

    public $role;

    public function mount(Role $role)
    {
        $this->role = $role;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.role.delete');
    }

    public function destroy()
    {
        $this->authorize('delete', $this->role);

        $name = $this->role->name;
        $id = $this->role->id;
        $this->role->delete();

        $this->emit('message', 'Role '.$name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-role-'.$id);
        $this->emit('refresh-table');
    }
}
