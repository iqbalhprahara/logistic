<?php

namespace App\Http\Livewire\CoreSystem;

use Core\Auth\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ChangePassword extends Component
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

        $this->emit('message', 'Your password successfully updated');
        $this->emit('close-modal', '#change-password-modal');
    }
}
