<?php

namespace App\Filament\AppPanel\Resources\UserManagement\RoleResource\Pages;

use App\Dto\Role\CreateRoleDto;
use App\Filament\AppPanel\Resources\UserManagement\RoleResource;
use App\Services\RoleService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ManageRoles extends ManageRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->authorize('user-management:role:create')
                ->mutateFormDataUsing(function (array $data) {
                    $data['permissions'] = Arr::collapse(Arr::except($data, 'name'));

                    return $data;
                })
                ->using(fn (RoleService $roleService, array $data) => DB::transaction(fn () => $roleService->createRole(CreateRoleDto::from($data)))),
        ];
    }
}
