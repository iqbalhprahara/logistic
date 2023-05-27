<?php

namespace App\Http\Livewire\CoreSystem\Home;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Dashboard extends BaseComponent
{
    public function render()
    {
        return view('livewire.core-system.home.dashboard')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Dashboard',
                'title' => 'Dashboard',
            ]);
    }
}
