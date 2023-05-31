<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
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

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-city-'.$id);
        $this->emit('refresh-table');
    }
}
