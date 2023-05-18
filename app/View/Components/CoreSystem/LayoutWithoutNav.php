<?php

namespace App\View\Components\CoreSystem;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LayoutWithoutNav extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $metaTitle = '',
        public string $bodyClass = ''
    ) {
        $this->metaTitle = $metaTitle ?: 'App | ' . config('app.name');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.core-system.layout-without-nav');
    }
}
