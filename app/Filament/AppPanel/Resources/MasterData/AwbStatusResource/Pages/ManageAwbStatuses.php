<?php

namespace App\Filament\AppPanel\Resources\MasterData\AwbStatusResource\Pages;

use App\Filament\AppPanel\Resources\MasterData\AwbStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAwbStatuses extends ManageRecords
{
    protected static string $resource = AwbStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('master-data:awb-status:create')->modalWidth('md'),
        ];
    }
}
