<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

class Edit extends BaseComponent
{
    protected $gates = ['master-data:location:province:edit'];

    public Province $province;

    public function mount(Province $province)
    {
        $this->province = $province;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.edit');
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

    public function update()
    {
        $this->validate($this->rules());

        $this->province->save();

        $this->emit('message', 'Province data successfully updated');
        $this->emit('close-modal', '#modal-edit-province-'.$this->province->id);
        $this->emit('refresh-table');
    }
}
