<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Admin;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;

class Delete extends BaseComponent
{
    protected $gates = ['administrative:access:admin:delete'];

    /** @var User */
    public $admin;

    public function mount(User $admin)
    {
        $this->admin = $admin;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.admin.delete');
    }

    public function destroy()
    {
        $name = $this->admin->name;
        $id = $this->admin->uuid;
        $this->admin->delete();

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-admin-'.$id);
        $this->dispatch('refresh-table');
    }
}
