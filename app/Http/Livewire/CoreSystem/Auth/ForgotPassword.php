<?php

namespace App\Http\Livewire\CoreSystem\Auth;

use App\Http\Livewire\BaseComponent;
use App\Jobs\SendResetPasswordNotificationJob;
use App\View\Components\CoreSystem\LayoutWithoutNav;
use Core\Auth\Models\User;

class ForgotPassword extends BaseComponent
{
    public string $email = '';

    protected $rules = [
        'email' => ['required', 'email'],
    ];

    public function render()
    {
        return view('livewire.core-system.auth.request_reset_password_link')
            ->layout(LayoutWithoutNav::class)
            ->layoutData([
                'metaTitle' => 'Reset Password',
                'bodyClass' => '',
            ]);
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail()
    {
        $validated = $this->validate();

        /** @var User */
        $user = User::whereEmail($validated['email'])->first();

        if ($user) {
            if (app('auth.password.broker')->getRepository()->recentlyCreatedToken($user)) {
                return $this->emit('error', __('passwords.throttled'));
            }

            SendResetPasswordNotificationJob::dispatch($user);

            $this->emit('message',
                __('passwords.sent'),
            );

            $this->reset();

            return;
        }

        $this->emit('error', __('passwords.user'));
        $this->addError('email', __('passwords.user'));
    }
}
