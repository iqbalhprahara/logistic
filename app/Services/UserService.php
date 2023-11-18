<?php

namespace App\Services;

use App\Dto\User\CreateAdminUserDto;
use App\Dto\User\CreateClientUserDto;
use App\Dto\User\UpdateAdminUserDto;
use App\Dto\User\UpdateClientUserDto;
use App\Models\Auth\User;

final class UserService
{
    protected ClientService $clientService;

    public function __construct()
    {
        $this->clientService = app(ClientService::class);
    }

    public function createAdminUser(CreateAdminUserDto $createAdminUserDto): User
    {
        $user = $this->create(
            $createAdminUserDto->name,
            $createAdminUserDto->email,
            $createAdminUserDto->password,
        );

        $user->syncRoles($createAdminUserDto->role);

        return $user;
    }

    public function updateAdminUser(UpdateAdminUserDto $updateAdminUserDto): User
    {
        $user = $this->update(
            $updateAdminUserDto->uuid,
            $updateAdminUserDto->name,
            $updateAdminUserDto->email,
            $updateAdminUserDto->changePassword,
            $updateAdminUserDto->password,
        );

        $user->syncRoles($updateAdminUserDto->role);

        return $user;
    }

    public function createClientUser(CreateClientUserDto $createClientUserDto): User
    {
        $user = $this->create(
            $createClientUserDto->name,
            $createClientUserDto->email,
            $createClientUserDto->password,
        );

        $user->assignRole('Client');

        $this->clientService->createClientForUser($user, $createClientUserDto->company);

        return $user;
    }

    public function updateClientUser(UpdateClientUserDto $updateClientUserDto)
    {
        $user = $this->update(
            $updateClientUserDto->uuid,
            $updateClientUserDto->name,
            $updateClientUserDto->email,
            $updateClientUserDto->changePassword,
            $updateClientUserDto->password,
        );

        $user->load('client');
        $this->clientService->updateCompanyForClient($user->client, $updateClientUserDto->company);

        return $user;
    }

    public function create(string $name, string $email, string $password): User
    {
        $user = new User;
        $user->fill(compact('name', 'email'));
        $user->email_verified_at = now();
        $user->changePassword($password);
        $user->save();

        return $user;
    }

    public function update(
        string $uuid,
        string $name,
        string $email,
        bool $changePassword = false,
        string $newPassword = null,
    ): User {
        $user = User::findOrFail($uuid);
        $user->fill(compact('name', 'email'));

        if ($changePassword) {
            $user->changePassword($newPassword);
        }

        $user->save();

        return $user;
    }
}
