<?php

namespace App\Http\Livewire\CoreSystem;

use Livewire\Component;

class Topbar extends Component
{
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.core-system.topbar');
    }

    public function logout()
    {
        auth()->logout();
        session()->flash('info', 'You have been logged out');
        return redirect()->route('login');
    }
}
