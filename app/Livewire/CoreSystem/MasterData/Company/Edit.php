<?php

namespace App\Livewire\CoreSystem\MasterData\Company;

use App\Livewire\BaseComponent;
use Core\MasterData\Models\Company;
use Illuminate\Validation\Rule;

class Edit extends BaseComponent
{
    protected $gates = ['master-data:company:edit'];

    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.core-system.master-data.company.edit');
    }

    public function updatedCompanyCode($value)
    {
        $this->company->code = strtoupper($value);
    }

    protected function rules()
    {
        return [
            'company.code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'code')->ignore($this->company->uuid, 'uuid'),
            ],
            'company.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function update()
    {
        $this->validate($this->rules());
        $this->company->save();

        $this->dispatch('message', message: 'Company data successfully updated');
        $this->dispatch('close-modal', modalId:  '#modal-edit-company-'.$this->company->uuid);
        $this->dispatch('refresh-table');
    }
}
