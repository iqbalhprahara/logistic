<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
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

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-province-'.$id);
        $this->emit('refresh-table');
    }
}
