<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\Subdistrict;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict:restore'];

    public ?int $subdistrictId;

    public function mount(int $id)
    {
        $this->subdistrictId = $id;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.restore');
    }

    public function restore()
    {
        $subdistrict = Subdistrict::withTrashed()->findOrFail($this->subdistrictId);
        $name = $subdistrict->name;
        $id = $subdistrict->id;
        $subdistrict->restore();

        $this->dispatch('message', message: $name.' successfully restored');
        $this->dispatch('close-modal', modalId:  '#modal-restore-subdistrict-'.$id);
        $this->dispatch('refresh-table');
    }
}
