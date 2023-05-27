<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\City;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:city:delete'];

    public City $city;

    public function mount(City $city)
    {
        $this->city = $city;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.delete');
    }

    public function destroy()
    {
        $name = $this->city->name;
        $id = $this->city->id;
        $this->city->delete();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-city-'.$id);
        $this->emit('refresh-table');
    }
}
