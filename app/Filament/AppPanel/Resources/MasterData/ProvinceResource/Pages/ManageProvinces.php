<?php

namespace App\Filament\AppPanel\Resources\MasterData\ProvinceResource\Pages;

use App\Filament\AppPanel\Resources\MasterData\ProvinceResource;
use App\Models\MasterData\Province;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageProvinces extends ManageRecords
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('master-data:province:create')->modalWidth('md'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(Province::withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(Province::onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
