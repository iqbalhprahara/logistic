<?php

namespace App\Livewire\CoreSystem\MasterData\Location\City;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\City;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:city:delete'];

    public ?int $cityId;

    public function mount(int $id)
    {
        $this->cityId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.delete');
    }

    public function destroy()
    {
        $city = City::findOrFail($this->cityId);
        $name = $city->name;
        $id = $city->id;
        $city->delete();

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-city-'.$id);
        $this->dispatch('refresh-table');
    }
}
