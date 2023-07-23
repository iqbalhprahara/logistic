<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

trait Sortable
{
    protected $disableSortable = false;

    public ?string $sortBy = null;

    public string $sortDir = 'asc';

    public function resetSorting()
    {
        $this->sortBy = null;
        $this->sortDir = 'asc';
    }

    public function updatedSortDir($value)
    {
        $this->sortDir = ! in_array($value, ['asc', 'desc']) ? 'asc' : $value;
    }

    protected function applySort(Builder|EloquentBuilder $query): Builder|EloquentBuilder
    {
        if ($this->disableSortable) {
            return $query;
        }

        if (null == $this->sortBy) {
            $firstCol = $this->columnDefinition()[0];
            $defaultCol = is_array($firstCol) ? $firstCol['column'] : $firstCol;
            $this->sortBy = $defaultCol;
        }

        $nullDir = $this->sortDir == 'asc' ? 'NULLS FIRST' : 'NULLS LAST';

        return $query->orderByRaw($this->sortBy.' '.$this->sortDir.' '.$nullDir);
    }
}
