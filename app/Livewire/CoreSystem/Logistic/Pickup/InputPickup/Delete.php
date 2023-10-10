<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Livewire\BaseComponent;
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
        if ($this->awb->isDelivered()) {
            $this->dispatch('error', message:  'Delivered AWB cannot be deleted');
            $this->dispatch('close-modal', modalId:  '#modal-delete-awb-'.$this->uuid);

            return;
        }

        $awb = Awb::findOrFail($this->uuid);
        $awb->delete();

        $this->dispatch('message', message: $awb->awb_no.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-awb-'.$this->uuid);
        $this->dispatch('refresh-table');
    }
}
