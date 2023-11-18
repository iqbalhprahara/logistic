<?php

namespace App\Enums;

use App\Filament\AppPanel\Resources\Logistic\ImportAwbLogResource;
use App\Imports\Logistic;

enum ImportType: string implements HasUrl
{
    case AWB = 'awb';

    public function importClass(): string
    {
        return match ($this) {
            self::AWB => Logistic\AwbImport::class,
        };
    }

    public function url(): string
    {
        return match ($this) {
            self::AWB => ImportAwbLogResource::getNavigationUrl(),
        };
    }
}
