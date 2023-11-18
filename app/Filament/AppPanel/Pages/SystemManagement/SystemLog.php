<?php

namespace App\Filament\AppPanel\Pages\SystemManagement;

use FilipFonal\FilamentLogManager\Pages\Logs;

final class SystemLog extends Logs
{
    protected static ?string $slug = 'system-management/system-log';

    public function mount(): void
    {
        abort_unless(auth()->user()->can('system-management:system-log'), 403);
    }
}
