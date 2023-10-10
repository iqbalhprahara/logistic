<?php

namespace App\Livewire\CoreSystem\Administrative\Access\Admin;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Spatie\Permission\Models\Role;

class Index extends BaseComponent
{
    protected $gates = ['administrative:access:admin'];

    public function render()
    {
        $roleList = Role::whereGuardName('web')
            ->whereNot('name', 'Client')
            ->pluck('name', 'id');

        return view('livewire.core-system.administrative.access.admin.index', compact('roleList'))
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Admin',
                'title' => 'Admin List',
            ]);
    }
}
