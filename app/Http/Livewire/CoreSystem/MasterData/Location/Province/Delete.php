<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:province:delete'];

    public Province $province;

    public function mount(Province $province)
    {
        $this->province = $province;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.delete');
    }

    public function destroy()
    {
        $name = $this->province->name;
        $id = $this->province->id;
        $this->province->delete();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-province-'.$id);
        $this->emit('refresh-table');
    }
}
