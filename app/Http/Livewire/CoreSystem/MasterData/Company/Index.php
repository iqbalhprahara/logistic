<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Company;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends BaseComponent
{
    use AuthorizesRequests;

    protected $gates = ['master-data:company'];

    public function render()
    {
        return view('livewire.core-system.master-data.company.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Company',
                'title' => 'Company List',
            ]);
    }
}
