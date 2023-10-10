<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Province;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:province:delete'];

    public ?int $provinceId;

    public function mount(int $id)
    {
        $this->provinceId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.delete');
    }

    public function destroy()
    {
        $province = Province::findOrFail($this->provinceId);

        $name = $province->name;
        $id = $province->id;
        $province->delete();

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-province-'.$id);
        $this->dispatch('refresh-table');
    }
}
