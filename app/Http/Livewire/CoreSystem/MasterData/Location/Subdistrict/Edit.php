<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;
use Illuminate\Support\Collection;

class Edit extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:edit'];

    public Subdistrict $subdistrict;

    public $province;

    public Collection $provinceList;

    public Collection $cityList;

    protected $validationAttributes = [
        'subdistrict.city_id' => 'City',
    ];

    public function mount(Subdistrict $subdistrict, $provinceList)
    {
        $this->provinceList = $provinceList;
        $this->subdistrict = $subdistrict;
        $this->province = $subdistrict->province_id;
        $this->initializeCityList();
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectProvinceEditSubdistrict', $this->subdistrict->id);
        $this->emitSelf('initSelectCityEditSubdistrict', $this->subdistrict->id);
    }

    public function updatedProvince($value)
    {
        $this->initializeCityList();
    }

    protected function getCityList()
    {
        return optional($this->provinceList->firstWhere('id', $this->province))->cities ?? collect();
    }

    public function initializeCityList()
    {
        $this->cityList = $this->getCityList();
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.edit');
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

    public function update()
    {
        $this->validate($this->rules());

        $this->subdistrict->save();

        $this->emit('message', 'Subdistrict data successfully updated');
        $this->emit('close-modal', '#modal-edit-subdistrict-'.$this->subdistrict->id);
        $this->emit('refresh-table');
    }
}
