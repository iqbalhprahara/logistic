<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Province;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:province:restore'];

    public ?int $provinceId;

    public function mount(int $id)
    {
        $this->provinceId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.restore');
    }

    public function restore()
    {
        $province = Province::withTrashed()->find($this->provinceId);
        $name = $province->name;
        $id = $province->id;
        $province->restore();

        $this->dispatch('message', message: $name.' successfully restored');
        $this->dispatch('close-modal', modalId:  '#modal-restore-province-'.$id);
        $this->dispatch('refresh-table');
    }
}
