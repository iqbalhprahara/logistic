<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class Edit extends BaseComponent
{
    protected $gates = ['master-data:location:city:edit'];

    public City $city;

    public Collection $provinceList;

    protected $validationAttributes = [
        'city.province_id' => 'Province',
    ];

    public function mount(City $city, $provinceList)
    {
        $this->city = $city;
        $this->provinceList = $provinceList;
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectProvinceEditCity', $this->city->id);
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.edit');
    }

    protected function rules()
    {
        return [
            'city.province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'city.code' => [
                'required',
                'string',
                'min:3',
                'max:3',
                Rule::unique('cities', 'code')->ignore($this->city->id),
            ],
            'city.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
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
