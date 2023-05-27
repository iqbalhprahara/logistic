<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Create extends BaseComponent
{
    protected $gates = ['master-data:location:province:create'];

    public Province $province;

    public function mount()
    {
        $this->initializeProvince();
    }

    public function initializeProvince()
    {
        $this->province = new Province();
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

        $this->initializeProvince();

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-province');
        $this->emit('refresh-table');
    }
}
