<?php

namespace App\Services;

use App\Models\Auth\User;
use App\Models\MasterData\Client;
use App\Models\MasterData\Company;

final class ClientService
{
    public function createClientForUser(User $user, Company $company)
    {
        return $user->client()->create([
            'company_uuid' => $company->uuid,
        ]);
    }

    public function updateCompanyForClient(Client $client, Company $newCompany)
    {
        return $client->update(['company_uuid' => $newCompany->uuid]);
    }
}
