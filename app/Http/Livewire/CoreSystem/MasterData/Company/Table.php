<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Company;

use App\Http\Livewire\BaseTableComponent;
use Core\MasterData\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['master-data:company'];

    public ?string $sortBy = 'uuid';

    protected function formatResult($row): array
    {
        return [
            'uuid' => $row->uuid,
            'code' => $row->code,
            'name' => $row->name,
            'users_count' => intval($row->users_count).' users',
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.master-data.company.action', ['company' => $row]),
        ];
    }

    public function button(): array
    {
        return [
            Blade::render(<<<'blade'
                @can('master-data:company:create')
                    @livewire('core-system.master-data.company.create')
                @endcan
            blade),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'column' => 'uuid',
            ],
            [
                'column' => 'code',
            ],
            [
                'column' => 'name',
            ],
            [
                'column' => 'users_count',
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
        return Company::withTrashed()
            ->withCount([
                'users',
            ]);
    }
}
