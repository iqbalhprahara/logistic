<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Client;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class Restore extends BaseComponent
{
    protected $gates = ['administrative:access:client:restore'];

    /** @var User */
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.client.restore');
    }

    public function restore()
    {
        $name = $this->user->name;
        $id = $this->user->uuid;

        DB::transaction(function () {
            $this->user->company()->restore();
            $this->user->restore();
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->dispatch('message', message: $name.' successfully restored');
        $this->dispatch('close-modal', modalId:  '#modal-restore-user-'.$id);
        $this->dispatch('refresh-table');
    }
}
