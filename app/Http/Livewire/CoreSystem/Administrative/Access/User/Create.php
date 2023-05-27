<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class Create extends BaseComponent
{
    protected $gates = ['administrative:access:admin:create'];

    /** @var User */
    public $admin;

    public $role;

    public $password;

    public $passwordConfirmation;

    public Collection $roleList;

    public function mount(Collection $roleList)
    {
        $this->roleList = $roleList;
        $this->initializeAdmin();
    }

    public function updatedRole($value)
    {
        $this->role = intval($value);
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectRole');
    }

    public function initializeAdmin()
    {
        $this->admin = new User();
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.admin.create');
    }

    protected function rules()
    {
        return [
            'admin.name' => [
                'required',
                'string',
            ],
            'admin.email' => [
                'required',
                'email:rfc,dns,spoof',
                'max:255',
                'unique:users,email',
            ],
            'role' => [
                'required',
                Rule::exists('roles', 'id')
                    ->whereNot('name', 'Client'),
            ],
            'password' => [
                'required',
                'same:passwordConfirmation',
                'different:currentPassword',
                Password::defaults(),
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $this->admin->email_verified_at = now();
            $this->admin->changePassword($this->password);
            $this->admin->save();
            $this->admin->syncRoles($this->role);
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $name = $this->admin->name;

        $this->initializeAdmin();
        $this->reset('role');

        $this->emit('message', 'Admin '.$name.' successfully created');
        $this->emit('close-modal', '#modal-create-admin');
        $this->emit('refresh-table');
    }
}
