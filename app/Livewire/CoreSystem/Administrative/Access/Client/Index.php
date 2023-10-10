<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Client;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Core\MasterData\Models\Company;
use Illuminate\Support\Facades\DB;

class Index extends BaseComponent
{
    protected $gates = ['administrative:access:client'];

    public function render()
    {
        $companyList = Company::select([
            DB::raw("concat(code, ' - ', name) as text"),
            'uuid as value',
        ])->pluck('text', 'value');

        return view('livewire.core-system.administrative.access.client.index', compact('companyList'))
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'User',
                'title' => 'User List',
            ]);
    }
}
