<?php

namespace App\Models\Logistic;

use App\Enums\AwbSource;
use App\Models\Concerns\CreatedBy;
use App\Models\MasterData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Awb extends Model
{
    use CreatedBy, HasFactory, HasUuids, LogsActivity, SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_cod' => false,
        'is_insurance' => false,
        'package_koli' => 1,
        'package_weight' => 1,
        'package_length' => 1,
        'package_width' => 1,
        'package_height' => 1,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'source' => AwbSource::class,
        'transporation_id' => 'string',
        'shipment_type_id' => 'string',
        'service_id' => 'string',
        'packing_id' => 'string',
        'is_cod' => 'boolean',
        'is_insurance' => 'boolean',
        'origin_province_id' => 'integer',
        'origin_city_id' => 'integer',
        'origin_subdistrict_id' => 'integer',
        'origin_zipcode' => 'string',
        'destination_province_id' => 'integer',
        'destination_city_id' => 'integer',
        'destination_subdistrict_id' => 'integer',
        'destination_zipcode' => 'string',
        'package_koli' => 'integer',
        'package_weight' => 'decimal:2',
        'package_length' => 'decimal:2',
        'package_width' => 'decimal:2',
        'package_height' => 'decimal:2',
        'package_value' => 'decimal:2',
        'awb_status_id' => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public const REF_NO_MAX_LENGTH = 50;

    public const ZIPCODE_DIGIT = 5;

    public const ADDRESS_MAX_LENGTH = 150;

    public const CONTACT_NAME_MAX_LENGTH = 100;

    public const PHONE_MIN_DIGIT = 9;

    public const PHONE_MAX_DIGIT = 15;

    public const MIN_KOLI = 1;

    public const MAX_KOLI = 10000;

    /** in Kg */
    public const MIN_WEIGHT = 1;

    /** in Kg */
    public const MAX_WEIGHT = 10000;

    /** in cm */
    public const MIN_DIMENSION = 0.01;

    /** in cm */
    public const MAX_DIMENSION = 10000;

    public const PACKAGE_DESC_MAX_LENGTH = 100;

    /** in Rp */
    public const MAX_PACKAGE_VALUE = 100000000000000000;

    public const PACKAGE_INSTRUCTION_MAX_LENGTH = 100;

    public static function boot()
    {
        static::addGlobalScope('clients', function ($builder) {
            $builder->when(optional(Auth::user())->isClient(), function ($q) {
                return $q->client(Auth::user()->client()->value('uuid'));
            });
        });

        parent::boot();
    }

    public static function defaultStatus(): int
    {
        return MasterData\AwbStatus::AWAITING_PICKUP;
    }

    public function scopeDelivered(Builder $query)
    {
        return $query->where('awb_status_id', MasterData\AwbStatus::DELIVERED);
    }

    public function scopeNotDelivered(Builder $query)
    {
        return $query->where('awb_status_id', '!=', MasterData\AwbStatus::DELIVERED);
    }

    public function scopeClient($builder, $clientUuid)
    {
        return $builder->where($this->getTable().'.client_uuid', $clientUuid);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }

    public function client()
    {
        return $this->belongsTo(MasterData\Client::class)->withTrashed();
    }

    public function transportation()
    {
        return $this->belongsTo(MasterData\Transportation::class);
    }

    public function shipmentType()
    {
        return $this->belongsTo(MasterData\ShipmentType::class);
    }

    public function service()
    {
        return $this->belongsTo(MasterData\Service::class);
    }

    public function packing()
    {
        return $this->belongsTo(MasterData\Packing::class);
    }

    public function originProvince()
    {
        return $this->belongsTo(MasterData\Province::class, 'origin_province_id')->withTrashed();
    }

    public function originCity()
    {
        return $this->belongsTo(MasterData\City::class, 'origin_city_id')->withTrashed();
    }

    public function originSubdistrict()
    {
        return $this->belongsTo(MasterData\Subdistrict::class, 'origin_subdistrict_id')->withTrashed();
    }

    public function destinationProvince()
    {
        return $this->belongsTo(MasterData\Province::class, 'destination_province_id')->withTrashed();
    }

    public function destinationCity()
    {
        return $this->belongsTo(MasterData\City::class, 'destination_city_id')->withTrashed();
    }

    public function destinationSubdistrict()
    {
        return $this->belongsTo(MasterData\Subdistrict::class, 'destination_subdistrict_id')->withTrashed();
    }

    public function awbStatus()
    {
        return $this->belongsTo(MasterData\AwbStatus::class);
    }

    public function awbStatusHistories()
    {
        return $this->hasMany(AwbStatusHistory::class);
    }

    public function getDestinationAddressForPrintAttribute()
    {
        return strlen($this->destination_address_line1) > 100
            ? substr($this->destination_address_line1, 0, 100).'...'
            : $this->destination_address_line1;
    }

    public function getDestinationAltAddressForPrintAttribute()
    {
        return strlen($this->destination_address_line2) > 100
            ? substr($this->destination_address_line2, 0, 100).'...'
            : $this->destination_address_line2;
    }

    public function getPackageDescForPrintAttribute()
    {
        return strlen($this->package_desc) > 100
            ? substr($this->package_desc, 0, 100).'...'
            : $this->package_desc;
    }

    public function isDelivered()
    {
        return $this->awb_status_id === MasterData\AwbStatus::DELIVERED;
    }

    public function getStatusColor()
    {
        $statusColor = 'gray';

        if ($this->awb_status_id === MasterData\AwbStatus::DELIVERED) {
            $statusColor = 'success';
        }

        if ($this->awb_status_id === MasterData\AwbStatus::UNDELIVERED) {
            $statusColor = 'danger';
        }

        if ($this->awb_status_id === MasterData\AwbStatus::RETURN) {
            $statusColor = 'warning';
        }

        return $statusColor;
    }

    public function getPackageDimensionAttribute(): string
    {
        return ($this->package_length + 0).' x '.($this->package_width + 0).' x '.($this->package_height + 0);
    }
}
