<?php

namespace App\Livewire\CoreSystem\MasterData\Company;

use App\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\MasterData\Models\Company;

class Restore extends BaseComponent
{
    protected $gates = ['master-data:company:restore'];

    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.company.restore');
    }

    public function restore()
    {
        $name = $this->company->name;
        $id = $this->company->uuid;
        $this->company->restore();

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->dispatch('message', message: $name.' successfully restored');
        $this->dispatch('close-modal', modalId:  '#modal-restore-company-'.$id);
        $this->dispatch('refresh-table');
    }
}
