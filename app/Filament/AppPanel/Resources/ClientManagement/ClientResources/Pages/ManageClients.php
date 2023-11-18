<?php

namespace App\Filament\AppPanel\Resources\ClientManagement\ClientResources\Pages;

use App\Dto\User\CreateClientUserDto;
use App\Filament\AppPanel\Resources\ClientManagement\ClientResource;
use App\Models\MasterData\Client;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ManageClients extends ManageRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->authorize('client-mangement:client:create')
                ->using(fn (UserService $userService, array $data) => DB::transaction(fn () => $userService->createClientUser(CreateClientUserDto::from($data)))),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(Client::withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(Client::onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
