<?php

namespace App\View\Components\CoreSystem;

use Core\Auth\MenuRegistry;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $user;

    public $menus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->user();
        $this->menus = $this->getMenus();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.core-system.sidebar');
    }

    protected function getMenus(): null|array
    {
        $menuData = app(MenuRegistry::class)
            ->getMenusByParams(['user_uuid' => $this->user->uuid, 'guard' => 'web'], true);

        return optional($menuData)['menus'];
    }
}
