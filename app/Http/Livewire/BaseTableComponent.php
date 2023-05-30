<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\HasSearch;
use App\Http\Livewire\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Livewire\WithPagination;

abstract class BaseTableComponent extends BaseComponent
{
    use WithPagination, Sortable, HasSearch;

    protected const DEFAULT_PAGINATION_LIMIT = 10;

    protected $listeners = ['refresh-table' => 'refreshTable'];

    public function render()
    {
        return view($this->view() ?? 'livewire.table', [
            'results' => $this->getResult(),
            'buttons' => $this->button(),
        ]);
    }

    public function view(): string|null
    {
        return null;
    }

    public function paginationKey(): string
    {
        return 'page';
    }

    public function getResult()
    {
        $query = $this->query();
        $query = $this->applyFilter($query);
        $query = $this->applySort($query);

        return $query->paginate(perPage: static::DEFAULT_PAGINATION_LIMIT, pageName: $this->paginationKey());
    }

    protected function button(): array
    {
        return [];
    }

    abstract protected function formatResult($row): array;

    abstract protected function columnDefinition(): array;

    abstract protected function query(): EloquentBuilder|Builder;

    public function paginationView()
    {
        return 'livewire.pagination.custom';
    }

    public function refreshTable()
    {
        $this->resetSorting();
        $this->resetFilter();
        $this->resetPage();
    }
}
