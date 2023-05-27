<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:restore'];

    public Subdistrict $subdistrict;

    public function mount(Subdistrict $subdistrict)
    {
        $this->subdistrict = $subdistrict;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.restore');
    }

    public function restore()
    {
        $name = $this->subdistrict->name;
        $id = $this->subdistrict->id;
        $this->subdistrict->restore();

        $this->emit('message', $name.' successfully restored');
        $this->emit('close-modal', '#modal-restore-subdistrict-'.$id);
        $this->emit('refresh-table');
    }
}
