<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use Core\MasterData\Models\City;

class Edit extends BaseForm
{
    protected $gates = ['master-data:location:city:edit'];

    public ?int $cityId;

    public function mount(int $id)
    {
        $this->cityId = $id;
    }

    public function initializeCityData()
    {
        $this->city = City::findOrFail($this->cityId);
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.edit');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->city->save();

        $this->emit('message', 'City data successfully updated');
        $this->emit('close-modal', '#modal-edit-city-'.$this->city->id);
        $this->emit('refresh-table');
    }
}
