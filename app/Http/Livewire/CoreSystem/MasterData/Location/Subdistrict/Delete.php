<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:delete'];

    public ?int $subdistrictId;

    public function mount(int $id)
    {
        $this->subdistrictId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.delete');
    }

    public function destroy()
    {
        $subdistrict = Subdistrict::findOrFail($this->subdistrictId);
        $name = $subdistrict->name;
        $id = $subdistrict->id;
        $subdistrict->delete();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-subdistrict-'.$id);
        $this->emit('refresh-table');
    }
}
