<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Livewire\BaseComponent;
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

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-subdistrict-'.$id);
        $this->dispatch('refresh-table');
    }
}
