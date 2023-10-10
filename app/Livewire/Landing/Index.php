<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.landing.layout')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.landing.index')
            ->title(config('app.name'));
    }
}
