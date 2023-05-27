<?php

namespace Core\Master\Concerns;

use Core\Master\Models\Company;

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
