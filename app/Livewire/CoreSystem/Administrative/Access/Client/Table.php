<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Client;

use App\Livewire\BaseTableComponent;
use Core\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Table extends BaseTableComponent
{
    protected $gates = ['administrative:access:client'];

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
            'client.company.name' => $row->client->company->name,
            'status' => $row->deleted_at ? '<span class="badge bg-danger">Deleted</span>' : '<span class="badge bg-primary">Active</span>',
            'action' => view('livewire.core-system.administrative.access.client.action', ['user' => $row, 'companyList' => $this->companyList]),
        ];
    }

    public function button(): array
    {
        $companyList = $this->companyList;

        return [
            Blade::render(<<<'blade'
                @can('administrative:access:client:create')
                    @livewire('core-system.administrative.access.client.create', compact('companyList'))
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
                'column' => 'client.company.name',
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
                'client',
                'client.company',
            ]);
    }
}
