<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Livewire\BaseComponent;
use Core\Logistic\Models\Awb;
use Core\Logistic\Models\AwbStatusHistory;
use Illuminate\Support\Collection;

class StatusHistory extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup'];

    public ?string $uuid;

    public ?string $awbNo;

    public ?Collection $awbStatusHistories;

    protected $listeners = ['initializeData' => 'initializeData'];

    public function mount(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function initializeData()
    {
        $this->awbNo = Awb::whereUuid($this->uuid)->value('awb_no');
        $this->awbStatusHistories = AwbStatusHistory::whereAwbUuid($this->uuid)
            ->with('awbStatus')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.status-history');
    }
}
