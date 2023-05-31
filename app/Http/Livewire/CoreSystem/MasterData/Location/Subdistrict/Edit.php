<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use Core\MasterData\Models\City;
use Core\MasterData\Models\Subdistrict;

class Edit extends BaseForm
{
    protected $gates = ['master-data:location:subdistrict:edit'];

    public ?int $subdistrictId;

    public function mount(int $id)
    {
        $this->subdistrictId = $id;
    }

    public function initializeSubdistrictData()
    {
        $this->subdistrict = Subdistrict::findOrFail($this->subdistrictId);
        $this->provinceId = City::whereId($this->subdistrict->city_id)->value('province_id');
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.edit');
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
