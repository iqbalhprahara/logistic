<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\Role;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends BaseComponent
{
    use AuthorizesRequests;

    protected $gates = ['administrative:access:role'];

    public function render()
    {
        return view('livewire.core-system.administrative.access.role.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Role & Permissions',
                'title' => 'Role & Permissions',
            ]);
    }
}
