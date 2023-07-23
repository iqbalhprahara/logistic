<?php

namespace Core\Logistic\Models;

use Core\MasterData\Models\AwbStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwbStatusHistory extends Model
{
    use HasFactory, HasUuids;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot()
    {
        static::creating(function (self $history) {
            $history->created_by = auth()->user()->uuid;
        });

        static::updating(function (self $history) {
            $history->updated_by = auth()->user()->uuid;
        });

        parent::boot();
    }

    public function awb()
    {
        return $this->belongsTo(Awb::class);
    }

    public function awbStatus()
    {
        return $this->belongsTo(AwbStatus::class);
    }
}
