<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class BaseComponent extends Component
{
    use AuthorizesRequests;

    protected $gates = [];

    public function boot()
    {
        $this->authorize($this->gates);
    }
}
