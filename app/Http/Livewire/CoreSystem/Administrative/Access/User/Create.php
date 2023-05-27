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
    public $user;

    public $company;

    public $password;

    public $passwordConfirmation;

    public Collection $companyList;

    public function mount(Collection $companyList)
    {
        $this->companyList = $companyList;
        $this->initializeUser();
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectCompany');
    }

    public function initializeUser()
    {
        $this->user = new User();
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.user.create');
    }

    protected function rules()
    {
        return [
            'user.name' => [
                'required',
                'string',
            ],
            'user.email' => [
                'required',
                'email:rfc,dns,spoof',
                'max:255',
                'unique:users,email',
            ],
            'company' => [
                'required',
                Rule::exists('companies', 'uuid'),
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
            $this->user->email_verified_at = now();
            $this->user->changePassword($this->password);
            $this->user->save();
            $this->user->assignRole('Client');
            $this->user->syncCompany($this->company);
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $name = $this->user->name;

        $this->initializeUser();
        $this->reset('company');

        $this->emit('message', 'User '.$name.' successfully created');
        $this->emit('close-modal', '#modal-create-user');
        $this->emit('refresh-table');
    }
}
