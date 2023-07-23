<?php

namespace Core\Logistic\Models;

use Core\MasterData\Models\AwbStatus;
use Core\MasterData\Models\City;
use Core\MasterData\Models\Client;
use Core\MasterData\Models\Packing;
use Core\MasterData\Models\Province;
use Core\MasterData\Models\Sequence;
use Core\MasterData\Models\Service;
use Core\MasterData\Models\ShipmentType;
use Core\MasterData\Models\Subdistrict;
use Core\MasterData\Models\Transportation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Awb extends Model
{
    use HasFactory, HasUuids, SoftDeletes, LogsActivity;

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function boot()
    {
        static::addGlobalScope('clients', function ($builder) {
            $builder->when(optional(Auth::user())->isClient(), function ($q) {
                return $q->client(Auth::user()->client()->value('uuid'));
            });
        });

        static::creating(function (self $awb) {
            $awb->awb_status_id = AwbStatus::AWAITING_PICKUP;
            $awb->created_by = auth()->user()->uuid;
        });

        static::updating(function (self $awb) {
            $awb->updated_by = auth()->user()->uuid;
        });

        static::saving(function (self $awb) {
            if (! $awb->awb_no) {
                $awb->awb_no = Sequence::generateCode('awb', now());
            }
        });

        parent::boot();
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
        return $this->belongsTo(Client::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }

    public function shipmentType()
    {
        return $this->belongsTo(ShipmentType::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function packing()
    {
        return $this->belongsTo(Packing::class);
    }

    public function originProvince()
    {
        return $this->belongsTo(Province::class, 'origin_province_id');
    }

    public function originCity()
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function originSubdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'origin_subdistrict_id');
    }

    public function destinationProvince()
    {
        return $this->belongsTo(Province::class, 'destination_province_id');
    }

    public function destinationCity()
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function destinationSubdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'destination_subdistrict_id');
    }

    public function awbStatus()
    {
        return $this->belongsTo(AwbStatus::class);
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
        return $this->awb_status_id === AwbStatus::DELIVERED;
    }

    public function getStatusColor()
    {
        $statusColor = 'secondary';

        if ($this->awb_status_id === AwbStatus::DELIVERED) {
            $statusColor = 'success';
        }

        if ($this->awb_status_id === AwbStatus::UNDELIVERED) {
            $statusColor = 'danger';
        }

        if ($this->awb_status_id === AwbStatus::RETURN) {
            $statusColor = 'primary';
        }

        return $statusColor;
    }
}
