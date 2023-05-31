<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Core\Logistic\Models\Awb;
use Core\MasterData\Models\City;
use Core\MasterData\Models\Client;
use Core\MasterData\Models\Packaging;
use Core\MasterData\Models\Province;
use Core\MasterData\Models\Service;
use Core\MasterData\Models\ShipmentType;
use Core\MasterData\Models\Subdistrict;
use Core\MasterData\Models\Transportation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

abstract class BaseForm extends BaseComponent
{
    public $viewOnly = false;

    public Awb $awb;

    public ?Collection $clientOptions = null;

    public ?Collection $transportationOptions = null;

    public ?Collection $shipmentTypeOptions = null;

    public ?Collection $serviceOptions = null;

    public ?Collection $packagingOptions = null;

    public ?Collection $originProvinceOptions = null;

    public ?Collection $originCityOptions = null;

    public ?Collection $originSubdistrictOptions = null;

    public ?Collection $destinationProvinceOptions = null;

    public ?Collection $destinationCityOptions = null;

    public ?Collection $destinationSubdistrictOptions = null;

    protected $listeners = ['initializeFormData' => 'initializeFormData'];

    public function initializeFormData()
    {
        $this->initializeAwbData();
        $this->initializeOptions();
    }

    public function initializeOptions()
    {
        if (! Auth::user()->isClient()) {
            $this->initializeClientOptions();
        }

        $this->initializeTransporationOptions();
        $this->initializeShipmentTypeOptions();
        $this->initializeServiceOptions();
        $this->initializePackagingOptions();
        $this->initializeOriginProvinceOptions();
        $this->initializeDestinationProvinceOptions();

        if ($this->awb->exists) {
            $this->initializeOriginCityOptions();
            $this->initializeOriginSubdistrictOptions();
            $this->initializeDestinationCityOptions();
            $this->initializeDestinationSubdistrictOptions();
        }
    }

    public function initializeClientOptions()
    {
        $this->clientOptions = Client::join('users', 'clients.user_uuid', '=', 'users.uuid')
            ->join('companies', 'clients.company_uuid', '=', 'companies.uuid')
            ->select([
                DB::raw('concat(users.name, \'-\', companies.name) as text'),
                'clients.uuid as value',
            ])
            ->pluck('text', 'value');
    }

    public function initializeTransporationOptions()
    {
        $this->transportationOptions = Transportation::pluck('name', 'id');

        if (! $this->awb->transportation_id) {
            $this->awb->transportation_id = $this->transportationOptions->keys()->first();
        }
    }

    public function initializeShipmentTypeOptions()
    {
        $this->shipmentTypeOptions = ShipmentType::pluck('name', 'id');

        if (! $this->awb->shipment_type_id) {
            $this->awb->shipment_type_id = $this->shipmentTypeOptions->keys()->first();
        }
    }

    public function initializeServiceOptions()
    {
        $this->serviceOptions = Service::pluck('id', 'id');

        if (! $this->awb->service_id) {
            $this->awb->service_id = $this->serviceOptions->first();
        }
    }

    public function initializePackagingOptions()
    {
        $this->packagingOptions = Packaging::pluck('name', 'id');
    }

    public function initializeOriginProvinceOptions()
    {
        $this->originProvinceOptions = Province::pluck('name', 'id');
    }

    public function initializeOriginCityOptions()
    {
        if ($this->awb->origin_province_id) {
            $this->originCityOptions = City::where('province_id', $this->awb->origin_province_id)->pluck('name', 'id');
            if (! $this->originCityOptions->keys()->contains($this->awb->origin_city_id)) {
                $this->awb->origin_city_id = null;
            }
        } else {
            $this->originCityOptions = collect();
        }
    }

    public function initializeOriginSubdistrictOptions()
    {
        if ($this->awb->origin_city_id) {
            $this->originSubdistrictOptions = Subdistrict::where('city_id', $this->awb->origin_city_id)->pluck('name', 'id');
            if (! $this->originSubdistrictOptions->keys()->contains($this->awb->origin_subdistrict_id)) {
                $this->awb->origin_subdistrict_id = null;
            }
        } else {
            $this->originSubdistrictOptions = collect();
        }
    }

    public function initializeDestinationProvinceOptions()
    {
        $this->destinationProvinceOptions = Province::pluck('name', 'id');
    }

    public function initializeDestinationCityOptions()
    {
        if ($this->awb->destination_province_id) {
            $this->destinationCityOptions = City::where('province_id', $this->awb->destination_province_id)->pluck('name', 'id');
            if (! $this->destinationCityOptions->keys()->contains($this->awb->destination_city_id)) {
                $this->awb->destination_city_id = null;
            }
        } else {
            $this->destinationCityOptions = collect();
        }
    }

    public function initializeDestinationSubdistrictOptions()
    {
        if ($this->awb->destination_city_id) {
            $this->destinationSubdistrictOptions = Subdistrict::where('city_id', $this->awb->destination_city_id)->pluck('name', 'id');
            if (! $this->destinationSubdistrictOptions->keys()->contains($this->awb->destination_subdistrict_id)) {
                $this->awb->destination_subdistrict_id = null;
            }
        } else {
            $this->destinationSubdistrictOptions = collect();
        }
    }

    public function updatedAwbOriginProvinceId()
    {
        $this->initializeOriginCityOptions();
    }

    public function updatedAwbOriginCityId()
    {
        $this->initializeOriginSubdistrictOptions();
    }

    public function updatedAwbDestinationProvinceId()
    {
        $this->initializeDestinationCityOptions();
    }

    public function updatedAwbDestinationCityId()
    {
        $this->initializeDestinationSubdistrictOptions();
    }

    public function updatedAwbIsCod($value)
    {
        $this->awb->is_cod = $value === 'true';
    }

    public function updatedAwbIsInsurance($value)
    {
        $this->awb->is_insurance = $value === 'true';
    }

    protected function rules()
    {
        $rules = [
            'awb.ref_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'awb.transportation_id' => [
                'required',
                'exists:transportations,id',
            ],
            'awb.shipment_type_id' => [
                'required',
                'exists:shipment_types,id',
            ],
            'awb.service_id' => [
                'required',
                'exists:services,id',
            ],
            'awb.packaging_id' => [
                'nullable',
                'exists:packagings,id',
            ],
            'awb.is_cod' => [
                'required',
                'boolean',
            ],
            'awb.is_insurance' => [
                'required',
                'boolean',
            ],

            'awb.package_koli' => [
                'required',
                'integer',
                'min:1',
            ],
            'awb.package_weight' => [
                'required',
                'decimal:0,2',
            ],
            'awb.package_length' => [
                'required',
                'decimal:0,2',
            ],
            'awb.package_width' => [
                'required',
                'decimal:0,2',
            ],
            'awb.package_height' => [
                'required',
                'decimal:0,2',
            ],
            'awb.package_desc' => [
                'nullable',
                'string',
            ],
            'awb.package_value' => [
                'nullable',
                'decimal:0,2',
            ],
            'awb.package_instruction' => [
                'nullable',
                'string',
            ],

            'awb.origin_province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'awb.origin_city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('province_id', $this->awb->origin_province_id),
            ],
            'awb.origin_subdistrict_id' => [
                'required',
                Rule::exists('subdistricts', 'id')
                    ->where('city_id', $this->awb->origin_city_id),
            ],
            'awb.origin_zipcode' => [
                'nullable',
                'digits:5',
            ],
            'awb.origin_address_line1' => [
                'required',
                'string',
            ],
            'awb.origin_address_line2' => [
                'nullable',
                'string',
            ],
            'awb.origin_contact_name' => [
                'required',
                'string',
                'max:255',
            ],
            'awb.origin_contact_phone' => [
                'required',
                'digits_between:8,13',
            ],
            'awb.origin_contact_alt_phone' => [
                'nullable',
                'digits_between:8,13',
            ],

            'awb.destination_province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'awb.destination_city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('province_id', $this->awb->destination_province_id),
            ],
            'awb.destination_subdistrict_id' => [
                'required',
                Rule::exists('subdistricts', 'id')
                    ->where('city_id', $this->awb->destination_city_id),
            ],
            'awb.destination_zipcode' => [
                'nullable',
                'digits:5',
            ],
            'awb.destination_address_line1' => [
                'required',
                'string',
            ],
            'awb.destination_address_line2' => [
                'nullable',
                'string',
            ],
            'awb.destination_contact_name' => [
                'required',
                'string',
                'max:255',
            ],
            'awb.destination_contact_phone' => [
                'required',
                'digits_between:8,13',
            ],
            'awb.destination_contact_alt_phone' => [
                'nullable',
                'digits_between:8,13',
            ],
        ];

        if (! Auth::user()->isClient() && ! $this->awb->exists) {
            $rules['awb.client_uuid'] = [
                'required',
                'exists:clients,uuid',
            ];
        }

        return $rules;
    }
}
