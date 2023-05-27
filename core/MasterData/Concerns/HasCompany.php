<?php

namespace Core\MasterData\Concerns;

use Core\MasterData\Models\Company;

trait HasCompany
{
    public function company()
    {
        return $this->morphOne(
            Company::class,
            'model',
            'model_has_company',
            'model_id',
            'company_uuid',
        );
    }
}
