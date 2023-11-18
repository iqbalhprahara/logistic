<?php

namespace App\Dto\User;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\Permission\Models\Role;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class CreateAdminUserDto extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public Role $role,
        public string $password,
    ) {

    }
}
