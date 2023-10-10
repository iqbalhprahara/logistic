<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use Core\Logistic\Models\Awb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends BaseForm
{
    protected $gates = ['logistic:pickup:input-pickup:create'];

    public function initializeAwbData()
    {
        $this->awb = new Awb();
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.create');
    }

    public function store()
    {
        $this->validate($this->rules());

        $awb = DB::transaction(function () {
            if (Auth::user()->isClient()) {
                $this->awb->fill([
                    'client_uuid' => Auth::user()->client()->value('uuid'),
                ]);
            }

            $this->awb->save();
        });

        $awb = $this->awb->awb_no;

        $this->dispatch('message', message: 'AWB '.$awb.' created');
        $this->dispatch('close-modal', modalId:  '#modal-create-awb');
        $this->dispatch('refresh-table');
    }
}
