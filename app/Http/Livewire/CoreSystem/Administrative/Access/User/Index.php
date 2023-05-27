<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Core\MasterData\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Index extends BaseComponent
{
    use AuthorizesRequests;

    protected $gates = ['administrative:access:user'];

    public function render()
    {
        $companyList = Company::select([
            DB::raw("concat(code, ' - ', name) as text"),
            'uuid as value',
        ])->pluck('text', 'value');

        return view('livewire.core-system.administrative.access.user.index', compact('companyList'))
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'User',
                'title' => 'User List',
            ]);
    }
}
