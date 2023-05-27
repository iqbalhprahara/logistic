<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseComponent;
use Spatie\Permission\Models\Role;

class Delete extends BaseComponent
{
    protected $gates = ['administrative:access:role:delete'];

    /** @var Role */
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

        $id = $this->role->id;

        if ($this->role->users()->withTrashed()->count() > 0) {
            $this->emit('error', 'Cannot delete role. There are some users assigned to this role');
            $this->emit('close-modal', '#modal-delete-role-'.$id);

            return;
        }

        $name = $this->role->name;

        $this->role->delete();

        $this->emit('message', 'Role '.$name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-role-'.$id);
        $this->emit('refresh-table');
    }
}
