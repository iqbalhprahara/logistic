<?php

namespace App\View\Components\CoreSystem;

use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public readonly ?string $class = null,
        public readonly int $col = 12,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.core-system.card');
    }
}
