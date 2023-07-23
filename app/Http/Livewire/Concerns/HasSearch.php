<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Livewire\WithPagination;

trait HasSearch
{
    public array $advanceSearch = [];

    protected ?string $advanceSearchId;

    public ?string $searchKeywords;

    public bool $isAdvanceSearch = false;

    public function bootHasSearch()
    {
        $this->advanceSearchId = 'advance-search-'.uniqid();
        $advanceSearch = $this->advanceSearch();

        if (! empty($advanceSearch)) {
            foreach ($advanceSearch as $key => $value) {
                $this->advanceSearch[$key] = '';
            }
        }
    }

    public function updatedAdvanceSearch()
    {
        $this->isAdvanceSearch = false;
        foreach ($this->advanceSearch as $search) {
            if (! empty($search)) {
                $this->isAdvanceSearch = true;
            }
        }
    }

    public function resetFilter()
    {
        $this->searchKeywords = null;
    }

    public function updatingSearchKeywords()
    {
        if (in_array(WithPagination::class, class_uses_recursive($this))) {
            $this->resetPage(Str::camel(class_basename(static::class)).'Page');
        }
    }

    public function advanceSearch(): array
    {
        return [];
    }

    protected function applyFilter(Builder|EloquentBuilder $query): Builder|EloquentBuilder
    {
        if (! empty($this->searchKeywords)) {
            $query->where(function ($q) use ($query) {
                foreach ($this->columnDefinition() as $columnDef) {
                    if (boolval(data_get($columnDef, 'searchable', true)) !== false) {
                        $column = is_string($columnDef) ? $columnDef : $columnDef['column'];
                        $columns = explode('.', $column);

                        if ($query->getQuery()->from !== $columns[0] && strpos($column, '.') !== false && ! $this->checkIfSearchIsJoin($query, $column)) {
                            $relations = collect($columns);
                            $column = $relations->pop();

                            $q->orWhereHas($relations->join('.'), function ($relation) use ($column) {
                                $relation->where($column, 'ilike', '%'.$this->searchKeywords.'%');
                            });

                            continue;
                        }

                        $q->orWhere($column, 'ilike', '%'.$this->searchKeywords.'%');
                    }
                }
            });
        }

        $advanceSearchDef = $this->advanceSearch();
        if (! empty($advanceSearchDef)) {
            foreach ($advanceSearchDef as $key => $searchDef) {
                if (! empty($this->advanceSearch[$key])) {
                    $query->where($searchDef['column'], $this->advanceSearch[$key]);
                }
            }
        }

        return $query;
    }

    protected function checkIfSearchIsJoin(Builder|EloquentBuilder $query, string $column): bool
    {
        $columns = explode('.', $column);

        if (null === $query->getQuery()->joins) {
            return false;
        }

        foreach ($query->getQuery()->joins as $join) {
            if (strpos($join->table, $columns[0]) !== false) {
                return true;
            }
        }

        return false;
    }
}
