<?php

namespace App\Enums;

enum AwbSource: string implements HasColor
{
    use AsOption;

    case DEFAULT = 'default';
    case IMPORT = 'import';

    public function color(): string
    {
        return match ($this) {
            self::DEFAULT => 'gray',
            self::IMPORT => 'success',
        };
    }
}
