<?php

namespace App\Enums;

enum ImportDetailStatus: string implements HasColor, HasText
{
    use AsOption;

    case ON_PROCESS = 'on-process';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    public function color(): string
    {
        return match ($this) {
            self::ON_PROCESS => 'info',
            self::SUCCESS => 'success',
            self::FAILED => 'danger',
        };
    }

    public function text(): string
    {
        return match ($this) {
            self::ON_PROCESS => 'on process',
            self::SUCCESS => 'complete',
            self::FAILED => 'failed',
        };
    }
}
