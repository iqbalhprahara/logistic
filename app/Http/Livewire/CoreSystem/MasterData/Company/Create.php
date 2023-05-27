<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Company;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Company;

class Create extends BaseComponent
{
    protected $gates = ['administrative:access:admin:create'];

    public Company $company;

    public function mount()
    {
        $this->initializeCompany();
    }

    public function initializeCompany()
    {
        $this->company = new Company();
    }

    public function updatedCompanyCode($value)
    {
        $this->company->code = strtoupper($value);
    }

    public function render()
    {
        return view('livewire.core-system.master-data.company.create');
    }

    protected function rules()
    {
        return [
            'company.code' => [
                'required',
                'string',
                'max:255',
                'unique:companies,code',
            ],
            'company.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->rules());
        $this->company->save();

        $name = $this->company->name;

        $this->initializeCompany();

        $this->emit('message', $name.' successfully created');
        $this->emit('close-modal', '#modal-create-company');
        $this->emit('refresh-table');
    }
}
