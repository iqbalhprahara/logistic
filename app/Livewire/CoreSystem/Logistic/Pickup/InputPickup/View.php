<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use Core\Logistic\Models\Awb;

class View extends BaseForm
{
    public $viewOnly = true;

    protected $gates = ['logistic:pickup:input-pickup'];

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
        return view('livewire.core-system.logistic.pickup.input-pickup.view');
    }
}
