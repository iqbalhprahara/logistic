<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends BaseComponent
{
    use AuthorizesRequests;

    protected $gates = ['administrative:access:user'];

    public function render()
    {
        return view('livewire.core-system.administrative.access.user.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'User',
                'title' => 'User List',
            ]);
    }
}
