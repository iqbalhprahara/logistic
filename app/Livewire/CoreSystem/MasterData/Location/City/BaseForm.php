<?php

namespace App\Livewire\CoreSystem\MasterData\Location\City;

use App\Livewire\BaseComponent;
use Core\MasterData\Enums\CityType;
use Core\MasterData\Models\City;
use Core\MasterData\Models\Province;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;

abstract class BaseForm extends BaseComponent
{
    public City $city;

    public ?Collection $provinceOptions = null;

    public ?Collection $cityTypeOptions = null;

    protected $listeners = ['initializeFormData' => 'initializeFormData'];

    abstract public function initializeCityData();

    public function initializeFormData()
    {
        $this->initializeCityData();
        $this->initializeOptions();
    }

    public function initializeOptions()
    {
        $this->initializeCityTypeOptions();
        $this->initializeProvinceOptions();
    }

    public function initializeCityTypeOptions()
    {
        $this->cityTypeOptions = collect(array_column(CityType::cases(), 'value'))->keyBy(function ($item) {
            return $item;
        });
    }

    public function initializeProvinceOptions()
    {
        $this->provinceOptions = Province::pluck('name', 'id');
    }

    public function updatedCityCode($value)
    {
        $this->city->code = strtoupper($value);
    }

    protected function rules()
    {
        return [
            'city.province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'city.type' => [
                'required',
                new Enum(CityType::class),
            ],
            'city.code' => [
                'required',
                'string',
                'min:3',
                'max:3',
            ],
            'city.name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
