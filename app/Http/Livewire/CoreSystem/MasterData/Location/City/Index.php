<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\City;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Core\MasterData\Models\Province;

class Index extends BaseComponent
{
    protected $gates = ['master-data:location:city'];

    public function render()
    {
        $provinceList = Province::pluck('name', 'id');

        return view('livewire.core-system.master-data.location.city.index', compact('provinceList'))
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'City',
                'title' => 'City List',
            ]);
    }
}
