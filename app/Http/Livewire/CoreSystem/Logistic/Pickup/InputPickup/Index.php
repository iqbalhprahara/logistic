<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
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
