<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use Core\MasterData\Models\Province;

class Edit extends BaseForm
{
    protected $gates = ['master-data:location:province:edit'];

    public ?int $provinceId;

    public function mount(int $id)
    {
        $this->provinceId = $id;
    }

    public function initializeProvinceData()
    {
        $this->province = Province::findOrFail($this->provinceId);
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.edit');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->province->save();

        $this->emit('message', 'Province data successfully updated');
        $this->emit('close-modal', '#modal-edit-province-'.$this->province->id);
        $this->emit('refresh-table');
    }
}
