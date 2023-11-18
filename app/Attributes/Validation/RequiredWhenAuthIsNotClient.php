<?php

namespace App\Attributes\Validation;

use Attribute;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Support\Validation\ValidationPath;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredWhenAuthIsNotClient extends CustomValidationAttribute
{
    public function getRules(ValidationPath $path): array|object|string
    {
        if (!auth()->check() || !auth()->user()->isClient()) {
            return new Required();
        }

        return new Nullable();
    }
}
