<?php

namespace App\Filament\AppPanel\Resources\UserManagement\AdminResources\Pages;

use App\Dto\User\CreateAdminUserDto;
use App\Filament\AppPanel\Resources\UserManagement\AdminResource;
use App\Models\Auth\User;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ManageAdmins extends ManageRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->authorize('user-management:admin:create')
                ->using(fn (array $data, UserService $userService) => DB::transaction(fn () => $userService->createAdminUser(CreateAdminUserDto::from($data)))),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed())->badge(User::admin()->withoutTrashed()->count())->badgeColor('success'),
            'trash' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(User::admin()->onlyTrashed()->count())->badgeColor('danger'),
        ];
    }
}
