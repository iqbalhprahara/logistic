<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\City;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:city:restore'];

    public City $city;

    public function mount(City $city)
    {
        $this->city = $city;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.restore');
    }

    public function restore()
    {
        $name = $this->city->name;
        $id = $this->city->id;
        $this->city->restore();

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-city-'.$id);
        $this->emit('refresh-table');
    }
}
