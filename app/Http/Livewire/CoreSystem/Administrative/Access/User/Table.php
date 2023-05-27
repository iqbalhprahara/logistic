<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseTableComponent;
use Core\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['administrative:access:user'];

    public ?string $sortBy = 'uuid';

    public Collection $companyList;

    public function mount($companyList)
    {
        $this->companyList = $companyList;
    }

    protected function formatResult($row): array
    {
        return [
            'uuid' => $row->uuid,
            'name' => $row->name,
            'email' => $row->email,
            'companies.name' => optional($row->companies->first())->name ?? '-',
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.administrative.access.user.action', ['user' => $row, 'companyList' => $this->companyList]),
        ];
    }

    public function button(): array
    {
        $companyList = $this->companyList;

        return [
            Blade::render(<<<'blade'
                @can('administrative:access:user:create')
                    @livewire('core-system.administrative.access.user.create', compact('companyList'))
                @endcan
            blade, compact('companyList')),
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
                'header' => 'Company',
                'column' => 'companies.name',
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
        return User::whereHas('roles', function ($roles) {
            $roles->whereName('Client');
        })
            ->withTrashed()
            ->with([
                'companies',
            ]);
    }
}
