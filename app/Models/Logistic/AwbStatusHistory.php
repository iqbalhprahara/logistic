<?php

namespace App\Models\Logistic;

use App\Models\MasterData;
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
        'status_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'datetime',
    ];

    public function awb()
    {
        return $this->belongsTo(Awb::class);
    }

    public function awbStatus()
    {
        return $this->belongsTo(MasterData\AwbStatus::class);
    }
}
