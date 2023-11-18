<?php

namespace App\Enums;

enum ImportStatus: string implements HasColor, HasText
{
    use AsOption;

    case PENDING = 'pending';
    case ON_PROCESS = 'on-process';
    case COMPLETE = 'complete';
    case FAILED = 'failed';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'grey',
            self::ON_PROCESS => 'info',
            self::COMPLETE => 'success',
            self::FAILED => 'danger',
        };
    }

    public function text(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::ON_PROCESS => 'on process',
            self::COMPLETE => 'complete',
            self::FAILED => 'failed',
        };
    }
}
