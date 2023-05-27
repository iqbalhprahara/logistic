<?php

namespace Core\MasterData\Concerns;

use Core\MasterData\Models\Company;

trait HasCompany
{
    public function companies()
    {
        return $this->morphToMany(
            Company::class,
            'model',
            'model_has_companies',
            'model_uuid',
            'company_uuid',
        );
    }

    public function syncCompany(string $companyUuid)
    {
        $this->companies()->detach();
        $this->companies()->sync([
            'company_uuid' => $companyUuid,
        ]);
    }
}
