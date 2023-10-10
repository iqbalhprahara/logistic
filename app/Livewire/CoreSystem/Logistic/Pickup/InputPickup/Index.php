<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Index extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup'];

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Input Pickup',
                'title' => 'AWB List',
            ]);
    }
}
