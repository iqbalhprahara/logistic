<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use Core\MasterData\Models\Subdistrict;

class Create extends BaseForm
{
    protected $gates = ['master-data:location:subdistrict:create'];

    public function initializeSubdistrictData()
    {
        $this->subdistrict = new Subdistrict;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.create');
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->subdistrict->save();

        $name = $this->subdistrict->name;

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-subdistrict');
        $this->emit('refresh-table');
    }
}
