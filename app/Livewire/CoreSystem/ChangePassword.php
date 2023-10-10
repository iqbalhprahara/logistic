<?php

namespace App\Livewire\CoreSystem;

use App\Livewire\BaseComponent;
use Core\Auth\Models\User;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends BaseComponent
{
    public User $user;

    public $currentPassword;

    public $newPassword;

    public $newPasswordConfirmation;

    public function render()
    {
        return view('livewire.core-system.change_password');
    }

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function rules()
    {
        return [
            'currentPassword' => ['required', 'current_password'],
            'newPassword' => [
                'required',
                'same:newPasswordConfirmation',
                'different:currentPassword',
                Password::defaults(),
            ],
        ];
    }

    public function changePassword()
    {
        $this->validate($this->rules());

        $this->user->changePassword($this->newPassword);

        $this->dispatch('message', message: 'Your password successfully updated');
        $this->dispatch('close-modal', modalId:  '#change-password-modal');
    }
}
