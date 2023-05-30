<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseTableComponent;
use Core\MasterData\Models\Subdistrict;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['master-data:location:subdistrict'];

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
            'city_name' => $row->city_name,
            'name' => $row->name,
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.master-data.location.subdistrict.action', ['subdistrict' => $row, 'provinceList' => $this->provinceList]),
        ];
    }

    public function button(): array
    {
        $provinceList = $this->provinceList;

        return [
            Blade::render(<<<'blade'
                @can('master-data:location:subdistrict:create')
                    @livewire('core-system.master-data.location.subdistrict.create', compact('provinceList'))
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
                'header' => 'City',
                'column' => 'city_name',
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
        return Subdistrict::withTrashed()
            ->leftJoin('cities', 'subdistricts.city_id', '=', 'cities.id')
            ->leftJoin('provinces', 'cities.province_id', '=', 'provinces.id')
            ->select([
                'subdistricts.*',
                'cities.name as city_name',
                'cities.province_id',
                'provinces.name as province_name',
            ]);
    }
}
