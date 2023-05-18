<?php

namespace App\View\Components\CoreSystem;

use Illuminate\View\Component;

class Layout extends Component
{
    public $metaTitle;

    public $title;

    public $user;

    public $permissions;

    public $role;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($metaTitle = null, $title = null)
    {
        $this->metaTitle = $metaTitle ?? 'App | '.config('app.name');
        $this->title = $title;
        $this->user = auth()->user();
        $this->permissions = [];
        $this->role = implode(',', $this->user->roles->pluck('name')->toArray());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.core-system.layout');
    }
}
