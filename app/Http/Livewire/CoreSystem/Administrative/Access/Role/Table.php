<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseTableComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Role;

class Table extends BaseTableComponent
{
    protected $gates = ['administrative:access:role'];

    public ?string $sortBy = 'id';

    protected function formatResult($row): array
    {
        return [
            'name' => $row->name,
            'users' => $row->users_count.' users',
            'permissions' => ($row->name === 'Super Admin' ? 'All' : $row->permissions->count()).' access',
            'action' => view('livewire.core-system.administrative.access.role.action', ['role' => $row]),
        ];
    }

    public function button(): array
    {
        return [
            Blade::render(<<<'blade'
                @can('administrative:access:role:create')
                    @livewire('core-system.administrative.access.role.create')
                @endcan
            blade),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'header' => 'Role Name',
                'column' => 'name',
            ],
            [
                'header' => 'Used',
                'column' => 'users',
                'sortable' => false,
                'searchable' => false,
            ],
            [
                'header' => 'Permissions',
                'column' => 'permissions',
                'sortable' => false,
                'searchable' => false,
            ],
            [
                'column' => 'action',
                'type' => 'raw',
                'searchable' => false,
                'sortable' => false,
                'width' => 100,
            ],
        ];
    }

    protected function query(): Builder|QueryBuilder
    {
        return Role::whereGuardName('web')
            ->withCount([
                'users',
            ])
            ->with([
                'permissions',
            ]);
    }
}
