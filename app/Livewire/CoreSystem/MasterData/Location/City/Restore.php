<?php

namespace App\Livewire\CoreSystem\MasterData\Location\City;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\City;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:city:restore'];

    public ?int $cityId;

    public function mount(int $id)
    {
        $this->cityId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.restore');
    }

    public function restore()
    {
        $city = City::withTrashed()->findOrFail($this->cityId);
        $name = $city->name;
        $id = $city->id;
        $city->restore();

        $this->dispatch('message', message: $name.' successfully restored');
        $this->dispatch('close-modal', modalId:  '#modal-restore-city-'.$id);
        $this->dispatch('refresh-table');
    }
}
