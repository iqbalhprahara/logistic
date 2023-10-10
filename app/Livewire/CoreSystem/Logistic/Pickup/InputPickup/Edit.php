<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use Core\Logistic\Models\Awb;
use Illuminate\Support\Facades\DB;

class Edit extends BaseForm
{
    protected $gates = ['logistic:pickup:input-pickup:edit'];

    public ?string $uuid;

    public function mount(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function initializeAwbData()
    {
        $this->awb = Awb::with([
            'client.user',
            'client.company',
        ])->findOrFail($this->uuid);
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.edit');
    }

    public function update()
    {
        if ($this->awb->isDelivered()) {
            $this->dispatch('error', message:  'Delivered AWB cannot be updated');
            $this->dispatch('close-modal', modalId:  '#modal-edit-awb-'.$this->uuid);

            return;
        }

        $this->validate($this->rules());

        $awb = DB::transaction(function () {
            $this->awb->save();
        });

        $awb = $this->awb->awb_no;

        $this->dispatch('message', message: 'AWB '.$awb.' updated');
        $this->dispatch('close-modal', modalId:  '#modal-edit-awb-'.$this->uuid);
        $this->dispatch('refresh-table');
    }
}
