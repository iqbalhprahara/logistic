<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\MasterData;
use App\Models\MasterData\Client;
use App\Models\MasterData\Company;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes, \Znck\Eloquent\Traits\BelongsToThrough;

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

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function scopeClient(Builder $query): Builder
    {
        return $query->whereHas('roles', function ($roles) {
            $roles->select(DB::raw(1))->where('name', 'Client');
        });
    }

    public function scopeAdmin(Builder $query): Builder
    {
        return $query->whereDoesntHave('roles', function ($roles) {
            $roles->select(DB::raw(1))->where('name', 'Client');
        });
    }

    public function client(): HasOne
    {
        return $this->hasOne(MasterData\Client::class);
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Company::class,
            Client::class,
            'user_uuid',
            'uuid',
            'uuid',
            'company_uuid',
        );
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

        return MasterData\Company::whereExists(function ($client) {
            $client->select(DB::raw(1))
                ->from('clients')
                ->where('user_uuid', $this->uuid)
                ->whereRaw('clients.company_uuid = companies.uuid');
        })->value('name');
    }
}
