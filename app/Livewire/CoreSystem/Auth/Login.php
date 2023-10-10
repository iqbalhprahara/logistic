<?php

namespace App\Livewire\CoreSystem\Auth;

use App\Livewire\BaseComponent;
use App\View\Components\CoreSystem\LayoutWithoutNav;
use Core\Auth\Models\User;
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

        $user = User::withTrashed()->whereEmail($data['email'])->first();

        if (! $user) {
            $this->addError('email', 'The provided credentials do not match our records.');
            $this->dispatch('error', message:  'The provided credentials do not match our records.');
            $this->reset();

            return;
        }

        if ($user->deleted_at !== null) {
            $this->addError('email', 'Your account has been deactivated. Please contact admin for further information.');
            $this->dispatch('error', message:  'Your account has been deactivated. Please contact admin for further information.');
            $this->reset('password');

            return;
        }

        if (Auth::attempt(Arr::except($data, ['remember']), $data['remember'])) {
            request()->session()->regenerate();

            return redirect()->intended('app');
        }

        $this->reset('password');
        $this->addError('email', 'The provided credentials do not match our records.');
        $this->dispatch('error', message:  'The provided credentials do not match our records.');
    }
}
