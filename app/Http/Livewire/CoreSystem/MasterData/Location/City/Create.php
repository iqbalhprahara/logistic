<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use Core\MasterData\Models\City;

class Create extends BaseForm
{
    protected $gates = ['master-data:location:city:create'];

    public function initializeCityData()
    {
        $this->city = new City;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.create');
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->city->save();

        $name = $this->city->name;

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-city');
        $this->emit('refresh-table');
    }
}
