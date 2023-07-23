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

    protected $disableTools = false;

    protected $disablePagination = false;

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

    public function perPage(): int
    {
        return static::DEFAULT_PAGINATION_LIMIT;
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

        return $query->paginate(perPage: $this->perPage(), pageName: $this->paginationKey());
    }

    protected function button(): array
    {
        return [];
    }

    protected function showedColumn(): array
    {
        return collect($this->columnDefinition())->filter(function ($item) {
            if (! isset($item['type'])) {
                return true;
            }

            return $item['type'] !== 'hidden';
        })->all();
    }

    protected function showedColumnCount(): int
    {
        return count($this->showedColumn());
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
