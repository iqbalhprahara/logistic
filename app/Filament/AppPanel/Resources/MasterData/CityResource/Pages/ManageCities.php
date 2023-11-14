<?php

namespace App\Filament\AppPanel\Resources\MasterData\CityResource\Pages;

use App\Filament\AppPanel\Resources\MasterData\CityResource;
use App\Models\MasterData\City;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageCities extends ManageRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('master-data:city:create')->modalWidth('md'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(City::withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(City::onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
