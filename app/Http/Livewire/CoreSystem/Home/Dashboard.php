<?php

namespace App\Http\Livewire\CoreSystem\Home;

use App\View\Components\CoreSystem\Layout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
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
