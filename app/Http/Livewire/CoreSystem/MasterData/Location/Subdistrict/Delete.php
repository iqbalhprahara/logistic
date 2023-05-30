<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:delete'];

    public Subdistrict $subdistrict;

    public function mount(Subdistrict $subdistrict)
    {
        $this->subdistrict = $subdistrict;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.delete');
    }

    public function destroy()
    {
        $name = $this->subdistrict->name;
        $id = $this->subdistrict->id;
        $this->subdistrict->delete();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-subdistrict-'.$id);
        $this->emit('refresh-table');
    }
}
