<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Core\Logistic\Models\Awb;

class Delete extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup:delete'];

    public ?string $uuid;

    public function mount(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.delete');
    }

    public function destroy()
    {
        $awb = Awb::findOrFail($this->uuid);
        $awb->delete();

        $this->emit('message', $awb->awb_no.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-awb-'.$this->uuid);
        $this->emit('refresh-table');
    }
}
