<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\City;
use Illuminate\Support\Collection;

class Create extends BaseComponent
{
    protected $gates = ['master-data:location:city:create'];

    public City $city;

    public Collection $provinceList;

    protected $validationAttributes = [
        'city.province_id' => 'Province',
    ];

    public function mount($provinceList)
    {
        $this->provinceList = $provinceList;
        $this->initializeCity();
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectProvince');
    }

    public function updatedCityCode($value)
    {
        $this->city->code = strtoupper($value);
    }

    public function initializeCity()
    {
        $this->city = new City();
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.create');
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
                'unique:cities,code',
            ],
            'city.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->city->save();

        $name = $this->city->name;

        $this->initializeCity();

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-city');
        $this->emit('refresh-table');
    }
}
