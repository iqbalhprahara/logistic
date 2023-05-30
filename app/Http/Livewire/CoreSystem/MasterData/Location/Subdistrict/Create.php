<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;
use Illuminate\Support\Collection;

class Create extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:create'];

    public Subdistrict $subdistrict;

    public $province;

    public Collection $provinceList;

    public Collection $cityList;

    protected $validationAttributes = [
        'subdistrict.city_id' => 'City',
    ];

    public function mount($provinceList)
    {
        $this->provinceList = $provinceList;
        $this->initializeCityList();
        $this->initializeSubdistrict();
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectProvince');
        $this->emitSelf('initSelectCity');
    }

    public function updatedProvince($value)
    {
        $this->initializeCityList();
    }

    protected function getCityList()
    {
        return optional($this->provinceList->firstWhere('id', $this->province))->cities ?? collect();
    }

    public function initializeSubdistrict()
    {
        $this->subdistrict = new Subdistrict();
    }

    public function initializeCityList()
    {
        $this->cityList = $this->getCityList();
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.create');
    }

    protected function rules()
    {
        return [
            'subdistrict.city_id' => [
                'required',
                'exists:cities,id',
            ],
            'subdistrict.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->subdistrict->save();

        $name = $this->subdistrict->name;

        $this->initializeSubdistrict();

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-subdistrict');
        $this->emit('refresh-table');
    }
}
