<?php

namespace Core\Logistic\Models;

use Core\MasterData\Models\Sequence;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        static::creating(function (self $awb) {
            $awb->created_by = auth()->user()->uuid;
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
}
