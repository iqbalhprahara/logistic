<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use Core\MasterData\Models\City;
use Core\MasterData\Models\Province;
use Core\MasterData\Models\Subdistrict;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

abstract class BaseForm extends BaseComponent
{
    public ?int $provinceId = null;

    public Subdistrict $subdistrict;

    public ?Collection $provinceOptions = null;

    public ?Collection $cityOptions = null;

    protected $listeners = ['initializeFormData' => 'initializeFormData'];

    abstract public function initializeSubdistrictData();

    public function initializeFormData()
    {
        $this->initializeSubdistrictData();
        $this->initializeOptions();
    }

    public function initializeOptions()
    {
        $this->initializeProvinceOptions();

        if ($this->subdistrict->exists) {
            $this->initializeCityOptions();
        }
    }

    public function initializeProvinceOptions()
    {
        $this->provinceOptions = Province::pluck('name', 'id');
    }

    public function initializeCityOptions()
    {
        if ($this->provinceId) {
            $this->cityOptions = City::where('province_id', $this->provinceId)->pluck('name', 'id');
            if (! $this->cityOptions->keys()->contains($this->subdistrict->city_id)) {
                $this->subdistrict->city_id = null;
            }
        } else {
            $this->cityOptions = collect();
        }
    }

    public function updatedProvinceId()
    {
        $this->initializeCityOptions();
    }

    protected function rules()
    {
        return [
            'provinceId' => [
                'required',
                'exists:provinces,id',
            ],
            'subdistrict.city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('province_id', $this->provinceId),
            ],
            'subdistrict.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
