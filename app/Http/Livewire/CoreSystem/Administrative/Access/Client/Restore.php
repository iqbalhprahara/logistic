<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Client;

use App\Http\Livewire\BaseComponent;
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

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-user-'.$id);
        $this->emit('refresh-table');
    }
}
