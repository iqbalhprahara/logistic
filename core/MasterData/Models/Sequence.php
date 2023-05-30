<?php

namespace Core\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Sequences extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const RUNNING_NO_PAD_LENGTH = 5;

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public static function generateCode(string $type, Carbon $transactionDate): string
    {
        if (! in_array($type, config('sequence.type'))) {
            throw new \InvalidArgumentException('unknown sequence type '.$type);
        }

        $prefix = config('sequence.prefix.'.$type);
        if (! $prefix) {
            throw new \LogicException('Please configure prefix for type in '.$type);
        }

        $sequence = self::lockForUpdate()->where([
            'type' => $type,
            'date' => $transactionDate,
        ])->firstOr(function () use ($type, $transactionDate) {
            return self::create([
                'type' => $type,
                'date' => $transactionDate,
                'current_no' => 1,
            ])->refresh();
        });

        $dateCode = $transactionDate->format('Ymd');
        $runningNumber = str_pad($sequence->current_no, static::RUNNING_NO_PAD_LENGTH, '0', STR_PAD_LEFT);
        $sequence->increment('current_no');

        return __(':prefix:datecode:runningnumber', [
            'prefix' => $prefix,
            'datecode' => $dateCode,
            'runningnumber' => $runningNumber,
        ]);
    }
}
