<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Livewire\BaseComponent;
use Core\Logistic\Models\Awb;
use Core\Logistic\Models\AwbStatusHistory;
use Core\MasterData\Models\AwbStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InputStatus extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup:input-status'];

    public ?string $uuid;

    public ?string $currentStatus;

    public ?Collection $statusOptions;

    public ?Awb $awb;

    public ?AwbStatusHistory $awbStatusHistory;

    protected $rules = [
        'awbStatusHistory.awb_uuid' => 'required|exists:awbs,uuid',
        'awbStatusHistory.awb_status_id' => 'required|exists:awb_statuses,id',
        'awbStatusHistory.note' => 'nullable|string',
    ];

    protected $listeners = ['initializeData' => 'initializeData'];

    public function mount(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function initializeData()
    {
        $this->awb = Awb::findOrFail($this->uuid);
        $currentStatusId = Awb::whereUuid($this->uuid)->value('awb_status_id');
        $this->currentStatus = AwbStatus::whereId(intval($currentStatusId))->value('name');
        $this->statusOptions = AwbStatus::whereNot('id', intval($currentStatusId))->pluck('name', 'id');
        $this->awbStatusHistory = new AwbStatusHistory;
        $this->awbStatusHistory->awb_uuid = $this->uuid;
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.input-status');
    }

    public function store()
    {
        if ($this->awb->isDelivered()) {
            $this->dispatch('error', message:  'Cannot change status of already delivered AWB');
            $this->dispatch('close-modal', modalId:  '#modal-input-status-awb-'.$this->uuid);

            return;
        }

        $this->validate();

        $awbStatusHistory = DB::transaction(function () {
            $this->awb->awb_status_id = $this->awbStatusHistory->awb_status_id;
            $this->awb->save();

            $this->awbStatusHistory->save();

            return $this->awbStatusHistory;
        });

        $awb = $this->awb->awb_no;

        $this->dispatch('message', message: 'AWB '.$awb.' status successfully changed');
        $this->dispatch('close-modal', modalId:  '#modal-input-status-awb-'.$this->uuid);
        $this->dispatch('refresh-table');
    }
}
