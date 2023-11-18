<?php

namespace App\Filament\AppPanel\Resources\ClientManagement\CompanyResources\Pages;

use App\Filament\AppPanel\Resources\ClientManagement\CompanyResource;
use App\Models\MasterData\Company;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageCompanies extends ManageRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('client-mangement:company:create'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(Company::withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(Company::onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
