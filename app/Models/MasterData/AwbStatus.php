<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwbStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public const AWAITING_PICKUP = 1;

    public const DELIVERED = 5;

    public const UNDELIVERED = 6;

    public const RETURN = 7;
}
