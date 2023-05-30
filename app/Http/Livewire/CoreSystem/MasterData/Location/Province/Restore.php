<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:province:restore'];

    public Province $province;

    public function mount(Province $province)
    {
        $this->province = $province;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.restore');
    }

    public function restore()
    {
        $name = $this->province->name;
        $id = $this->province->id;
        $this->province->restore();

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-province-'.$id);
        $this->emit('refresh-table');
    }
}
