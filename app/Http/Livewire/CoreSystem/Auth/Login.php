<?php

namespace App\Http\Livewire\CoreSystem\Auth;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\LayoutWithoutNav;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Login extends BaseComponent
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
        'remember' => ['nullable', 'boolean'],
    ];

    public function render()
    {
        return view('livewire.core-system.auth.login')
            ->layout(LayoutWithoutNav::class)
            ->layoutData([
                'metaTitle' => 'Login',
                'bodyClass' => '',
            ]);
    }

    public function attempt()
    {
        $data = $this->validate();

        if (Auth::attempt(Arr::except($data, ['remember']), $data['remember'])) {
            request()->session()->regenerate();

            return redirect()->intended('app');
        }

        $this->reset('password');
        $this->addError('email', 'The provided credentials do not match our records.');
        $this->emit('error', 'The provided credentials do not match our records.');
    }
}
