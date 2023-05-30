<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Province;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Index extends BaseComponent
{
    protected $gates = ['master-data:location:province'];

    public function render()
    {
        return view('livewire.core-system.master-data.location.province.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Province',
                'title' => 'Province List',
            ]);
    }
}
