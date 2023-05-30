<?php

namespace App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Core\MasterData\Models\Province;

class Index extends BaseComponent
{
    protected $gates = ['master-data:location:subdistrict'];

    public function render()
    {
        $provinceList = Province::with([
            'cities',
        ])->get();

        return view('livewire.core-system.master-data.location.subdistrict.index', compact('provinceList'))
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Subdistrict',
                'title' => 'Subdistrict List',
            ]);
    }
}
