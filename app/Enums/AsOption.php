<?php

namespace App\Enums;

use Illuminate\Support\Collection;

trait AsOption
{
    public static function asOptions(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn ($item) => [$item->value => $item instanceof HasText ? $item->text() : $item->value]);
    }
}
