<?php

namespace App\Http\Livewire\CoreSystem\Auth;

use App\View\Components\CoreSystem\LayoutWithoutNav;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;

class PasswordReset extends Component
{
    public string $email;
    public string $token;
    public $password;
    public $passwordConfirmation;

    public function mount(Request $request, string $token)
    {
        $this->email = $request->email;
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.core-system.auth.password_reset')
            ->layout(LayoutWithoutNav::class)
            ->layoutData([
                'metaTitle' => 'Reset Password',
                'bodyClass' => '',
            ]);
    }

    protected function rules()
    {
        return [
            'password' => [
                'required',
                'same:passwordConfirmation',
                'different:currentPassword',
                PasswordRule::defaults(),
            ],
        ];
    }

    /**
     * Send a reset link to the given user.
     */
    public function changePassword()
    {
        $data = $this->validate();

        $response = Password::broker(config('ladmin.auth.broker'))->reset([
            'email' => $this->email,
            'token' => $this->token,
            'password' => $data['password'],
            'password_confirmation' => $this->passwordConfirmation,
        ], fn ($user, $password) => $user->changePassword($password, true, true));

        if ($response == Password::PASSWORD_RESET) {
            session()->flash('message', __($response));
            return redirect()->route('app.home');
        }

        $this->emit('warning', __($response));
    }
}
