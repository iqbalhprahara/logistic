<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use Core\Logistic\Models\Awb;
use Illuminate\Support\Facades\DB;

class Create extends BaseForm
{
    protected $gates = ['administrative:access:admin:create'];

    public function mount()
    {
        $this->initializeAwb();
    }

    public function initializeAwb()
    {
        $this->awb = new Awb();
    }

    public function updatedCompanyCode($value)
    {
        $this->company->code = strtoupper($value);
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.create');
    }

    public function store()
    {
        $this->validate($this->rules());

        $awb = DB::transaction(function () {
            $this->awb->save();
        });

        $awb = $this->awb->awb_no;

        $this->initializeAwb();

        $this->emit('message', 'AWB '.$awb.' created');
        $this->emit('close-modal', '#modal-create-awb');
        $this->emit('refresh-table');
    }
}
