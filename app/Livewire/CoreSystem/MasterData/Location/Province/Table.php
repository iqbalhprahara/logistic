<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Province;

use App\Livewire\BaseTableComponent;
use Core\MasterData\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['master-data:location:province'];

    public ?string $sortBy = 'id';

    protected function formatResult($row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.master-data.location.province.action', ['province' => $row]),
        ];
    }

    public function button(): array
    {
        return [
            Blade::render(<<<'blade'
                @can('master-data:location:province:create')
                    @livewire('core-system.master-data.location.province.create')
                @endcan
            blade),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'column' => 'id',
            ],
            [
                'column' => 'name',
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
        return Province::withTrashed();
    }
}
