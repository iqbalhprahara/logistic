<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\Province;

abstract class BaseForm extends BaseComponent
{
    public Province $province;

    protected $listeners = ['initializeFormData' => 'initializeFormData'];

    abstract public function initializeProvinceData();

    public function initializeFormData()
    {
        $this->initializeProvinceData();
    }

    protected function rules()
    {
        return [
            'province.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
