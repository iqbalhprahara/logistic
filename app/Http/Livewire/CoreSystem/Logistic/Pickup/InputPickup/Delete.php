<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Company;

class Delete extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup:delete'];

    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.delete');
    }

    public function destroy()
    {
        $id = $this->company->uuid;

        if ($this->company->users()->count() > 0) {
            $this->emit('error', 'Cannot delete company. There are some users assigned to this company');
            $this->emit('close-modal', '#modal-delete-company-'.$id);

            return;
        }

        $name = $this->company->name;

        $this->company->delete();

        $this->emit('message', $name.' successfully deleted');
        $this->emit('close-modal', '#modal-delete-company-'.$id);
        $this->emit('refresh-table');
    }
}
