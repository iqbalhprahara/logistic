<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Client;

use App\Http\Livewire\BaseComponent;
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

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-user-'.$id);
        $this->emit('refresh-table');
    }
}
