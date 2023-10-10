<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Role;

use App\Livewire\BaseComponent;
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
            $this->dispatch('error', message:  'Cannot delete role. There are some users assigned to this role');
            $this->dispatch('close-modal', modalId:  '#modal-delete-role-'.$id);

            return;
        }

        $name = $this->role->name;

        $this->role->delete();

        $this->dispatch('message', message: 'Role '.$name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-role-'.$id);
        $this->dispatch('refresh-table');
    }
}
