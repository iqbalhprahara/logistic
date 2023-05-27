<?php

namespace App\Http\Livewire\CoreSystem\Administrative\Access\User;

use App\Http\Livewire\BaseComponent;
use Core\Auth\MenuRegistry;
use Core\Auth\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Edit extends BaseComponent
{
    protected $gates = ['administrative:access:user:edit'];

    /** @var User */
    public $user;

    public $company;

    public Collection $companyList;

    public function mount(User $user, $companyList)
    {
        $this->user = $user;
        $this->company = optional($user->companies->first())->uuid;
        $this->companyList = $companyList;
    }

    public function hydrate()
    {
        $this->emitSelf('initSelectCompanyEditUser', $this->user->uuid);
    }

    public function render()
    {
        return view('livewire.core-system.administrative.access.user.edit');
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
                Rule::unique('users', 'email')
                    ->ignore($this->user->uuid, 'uuid'),
            ],
            'company' => [
                'required',
                Rule::exists('companies', 'uuid'),
            ],
        ];
    }

    public function update()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $this->user->save();
            $this->user->syncCompany($this->company);
        });

        app(MenuRegistry::class)->forgetCachedMenus();

        $this->emit('message', 'User data successfully updated');
        $this->emit('close-modal', '#modal-edit-user-'.$this->user->uuid);
        $this->emit('refresh-table');
    }
}
