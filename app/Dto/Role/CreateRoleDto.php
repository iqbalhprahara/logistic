<?php

namespace App\Dto\Role;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class CreateRoleDto extends Data
{
    public function __construct(
        public string $name,
        public array $permissions,
    ) {

    }
}
