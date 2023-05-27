<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;

class Delete extends BaseComponent
{
    protected $gates = ['administrative:access:user:delete'];

    /** @var User */
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.user.delete');
    }

    public function destroy()
    {
        $name = $this->user->name;
        $id = $this->user->uuid;
        $this->user->delete();

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-user-'.$id);
        $this->emit('refresh-table');
    }
}
