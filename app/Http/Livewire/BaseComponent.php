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
        if (! empty($this->gates)) {
            $this->authorize($this->gates);
        }
    }
}
