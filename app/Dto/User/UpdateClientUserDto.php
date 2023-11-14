<?php

namespace App\Dto\User;

use App\Models\MasterData\Company;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class UpdateClientUserDto extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $email,
        public Company $company,
        public bool $changePassword = false,
        public ?string $password = null,
    ) {

    }
}
