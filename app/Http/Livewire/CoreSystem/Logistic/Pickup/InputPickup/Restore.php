<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Core\Logistic\Models\Awb;

class Restore extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup:restore'];

    public ?string $uuid;

    public function mount(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.restore');
    }

    public function restore()
    {
        $awb = Awb::onlyTrashed()->findOrFail($this->uuid);
        $awb->restore();

        $this->emit('message', $awb->awb_no.' successfully restored');
        $this->emit('close-modal', '#modal-restore-awb-'.$this->uuid);
        $this->emit('refresh-table');
    }
}
