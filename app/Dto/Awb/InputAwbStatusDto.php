<?php

namespace App\Dto\Awb;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class InputAwbStatusDto extends Data
{
    public function __construct(
        public string $awbUuid,
        public int $awbStatusId,
        public Carbon $statusAt,
        public ?string $note = null,
    ) {
    }
}
