<?php

namespace App\Filament\AppPanel\Resources\SystemManagement;

use App\Filament\AppPanel\Resources\SystemManagement\EmailLogResources\Pages\ListEmailLogs;
use App\Filament\AppPanel\Resources\SystemManagement\EmailLogResources\Pages\ViewEmailLog;
use RickDBCN\FilamentEmail\Filament\Resources\EmailResource;

final class EmailLogResource extends EmailResource
{
    protected static ?string $slug = null;

    public static function canViewAny(): bool
    {
        return auth()->user()->can('system-management:email-log');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailLogs::route('/'),
            'view' => ViewEmailLog::route('/{record}'),
        ];
    }
}
