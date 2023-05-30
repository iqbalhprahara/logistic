<?php

namespace Core\Auth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Core\MasterData\Models\Client;
use Core\MasterData\Models\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Change user password with given new password
     *
     * @return void
     */
    public function changePassword(string $newPassword, bool $setRemember = false, bool $forceLogin = false)
    {
        $this->password = Hash::make($newPassword);

        if ($setRemember) {
            $this->setRememberToken(Str::random(60));
        }

        $this->save();

        if ($forceLogin) {
            Auth::login($this);
        }
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }

    public function isClient()
    {
        return $this->hasRole('Client');
    }

    public function getCompanyNameAttribute()
    {
        if ($this->relationLoaded('client.company')) {
            return optional($this->client->company)->name;
        }

        return Company::whereExists(function ($client) {
            $client->select(DB::raw(1))
                ->from('clients')
                ->where('user_uuid', $this->uuid)
                ->whereRaw('clients.company_uuid = companies.uuid');
        })->value('name');
    }
}
