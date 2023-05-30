<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseTableComponent;
use Core\MasterData\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['master-data:location:city'];

    public ?string $sortBy = 'id';

    public Collection $provinceList;

    public function mount($provinceList)
    {
        $this->provinceList = $provinceList;
    }

    protected function formatResult($row): array
    {
        return [
            'id' => $row->id,
            'province_name' => $row->province_name,
            'code' => $row->code,
            'name' => $row->name,
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.master-data.location.city.action', ['city' => $row, 'provinceList' => $this->provinceList]),
        ];
    }

    public function button(): array
    {
        $provinceList = $this->provinceList;

        return [
            Blade::render(<<<'blade'
                @can('master-data:location:city:create')
                    @livewire('core-system.master-data.location.city.create', compact('provinceList'))
                @endcan
            blade, compact('provinceList')),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'column' => 'id',
            ],
            [
                'header' => 'Province',
                'column' => 'province_name',
            ],
            [
                'column' => 'code',
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
        return City::withTrashed()
            ->leftJoin('provinces', 'cities.province_id', '=', 'provinces.id')
            ->select([
                'cities.*',
                'provinces.name as province_name',
            ]);
    }
}
