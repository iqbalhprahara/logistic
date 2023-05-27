<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;

class Restore extends BaseComponent
{
    protected $gates = ['administrative:access:admin:restore'];

    /** @var User */
    public $admin;

    public function mount(User $admin)
    {
        $this->admin = $admin;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.admin.restore');
    }

    public function restore()
    {
        $name = $this->admin->name;
        $id = $this->admin->uuid;
        $this->admin->restore();

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-admin-'.$id);
        $this->emit('refresh-table');
    }
}
