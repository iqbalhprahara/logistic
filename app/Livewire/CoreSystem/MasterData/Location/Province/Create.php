<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Province;

use Core\MasterData\Models\Province;

class Create extends BaseForm
{
    protected $gates = ['master-data:location:province:create'];

    public function initializeProvinceData()
    {
        $this->province = new Province;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.create');
    }

    protected function rules()
    {
        return [
            'province.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->province->save();

        $name = $this->province->name;

        $this->dispatch('message', message: $name.' successfully created');
        $this->dispatch('close-modal', modalId:  '#modal-create-province');
        $this->dispatch('refresh-table');
    }
}
