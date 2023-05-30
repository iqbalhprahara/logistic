<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Admin;

use App\Http\Livewire\BaseTableComponent;
use Core\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['administrative:access:admin'];

    public ?string $sortBy = 'uuid';

    public Collection $roleList;

    public function mount(Collection $roleList)
    {
        $this->roleList = $roleList;
    }

    protected function formatResult($row): array
    {
        return [
            'uuid' => $row->uuid,
            'name' => $row->name,
            'email' => $row->email,
            'roles.name' => optional($row->roles->first())->name ?? '-',
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.administrative.access.admin.action', ['admin' => $row, 'roleList' => $this->roleList]),
        ];
    }

    public function button(): array
    {
        $roleList = $this->roleList;

        return [
            Blade::render(<<<'blade'
                @can('administrative:access:admin:create')
                    @livewire('core-system.administrative.access.admin.create', compact('roleList'))
                @endcan
            blade, compact('roleList')),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'column' => 'uuid',
            ],
            [
                'column' => 'name',
            ],
            [
                'column' => 'email',
            ],
            [
                'header' => 'Role',
                'column' => 'roles.name',
                'sortable' => false,
            ],
            [
                'type' => 'raw',
                'column' => 'status',
                'searchable' => false,
                'sortable' => false,
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
        return User::whereDoesntHave('roles', function ($roles) {
            $roles->whereName('Client');
        })
            ->withTrashed()
            ->with([
                'roles',
            ]);
    }
}
