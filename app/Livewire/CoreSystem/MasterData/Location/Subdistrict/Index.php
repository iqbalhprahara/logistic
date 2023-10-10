<?php

namespace App\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Index extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict'];

    public function render()
    {
        return view('livewire.core-system.master-data.location.subdistrict.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Subdistrict',
                'title' => 'Subdistrict List',
            ]);
    }
}
