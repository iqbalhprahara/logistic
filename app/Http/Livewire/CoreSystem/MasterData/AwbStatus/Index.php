<?php

namespace App\Http\Livewire\CoreSystem\MasterData\AwbStatus;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;

class Index extends BaseComponent
{
    protected $gates = ['master-data:awb-status'];

    public function render()
    {
        return view('livewire.core-system.master-data.awb-status.index')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Awb Status',
                'title' => 'Awb Status List',
            ]);
    }
}
