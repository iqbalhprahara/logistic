<?php

namespace App\Services;

use App\Models\MasterData;
use Illuminate\Support\Carbon;

final class SequenceService
{
    public function generate(string $type, Carbon $date)
    {
        return MasterData\Sequence::generateCode($type, $date);
    }
}
