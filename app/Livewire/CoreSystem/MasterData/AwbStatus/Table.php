<?php

namespace App\Livewire\CoreSystem\MasterData\AwbStatus;

use App\Livewire\BaseTableComponent;
use Core\MasterData\Models\AwbStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Table extends BaseTableComponent
{
    protected $gates = ['master-data:awb-status'];

    public ?string $sortBy = 'id';

    protected function formatResult($row): array
    {
        return $row->only(['id', 'name']);
    }

    public function button(): array
    {
        return [
            //
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
        ];
    }

    protected function query(): Builder|QueryBuilder
    {
        return AwbStatus::query();
    }
}
