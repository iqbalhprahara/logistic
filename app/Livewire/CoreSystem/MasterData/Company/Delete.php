<?php

namespace App\Livewire\CoreSystem\MasterData\Company;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\Company;

class Delete extends BaseComponent
{
    protected $gates = ['master-data:company:delete'];

    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.company.delete');
    }

    public function destroy()
    {
        $id = $this->company->uuid;

        if ($this->company->users()->count() > 0) {
            $this->dispatch('error', message:  'Cannot delete company. There are some users assigned to this company');
            $this->dispatch('close-modal', modalId:  '#modal-delete-company-'.$id);

            return;
        }

        $name = $this->company->name;

        $this->company->delete();

        $this->dispatch('message', message: $name.' successfully deleted');
        $this->dispatch('close-modal', modalId:  '#modal-delete-company-'.$id);
        $this->dispatch('refresh-table');
    }
}
