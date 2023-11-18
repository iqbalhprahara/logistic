<?php

namespace App\Filament\AppPanel\Resources\SystemManagement\EmailLogResources\Pages;

use App\Filament\AppPanel\Resources\SystemManagement\EmailLogResource;
use Filament\Resources\Pages\ListRecords;

class ListEmailLogs extends ListRecords
{
    protected static string $resource = EmailLogResource::class;
}
