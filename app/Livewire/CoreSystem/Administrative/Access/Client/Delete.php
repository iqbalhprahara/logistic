<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Client;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class Delete extends BaseComponent
{
    protected $gates = ['administrative:access:client:delete'];

    /** @var User */
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.client.delete');
    }

    public function destroy()
    {
        $name = $this->user->name;
        $id = $this->user->uuid;

        DB::transaction(function () {
            $this->user->company()->delete();
            $this->user->delete();
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-user-'.$id);
        $this->dispatch('refresh-table');
    }
}
