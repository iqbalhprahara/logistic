<?php

namespace App\Filament\AppPanel\Resources\MasterData\SubdistrictResource\Pages;

use App\Filament\AppPanel\Resources\MasterData\SubdistrictResource;
use App\Models\MasterData\Subdistrict;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageSubdistricts extends ManageRecords
{
    protected static string $resource = SubdistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('master-data:subdistrict:create')->modalWidth('md'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(Subdistrict::withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(Subdistrict::onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
