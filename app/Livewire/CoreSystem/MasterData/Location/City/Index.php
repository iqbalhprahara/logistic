<?php

namespace App\Livewire\CoreSystem\MasterData\Location\City;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Index extends BaseComponent
{
    protected $gates = ['master-data:location:city'];

    public function render()
    {
        return view('livewire.core-system.master-data.location.city.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'City',
                'title' => 'City List',
            ]);
    }
}
