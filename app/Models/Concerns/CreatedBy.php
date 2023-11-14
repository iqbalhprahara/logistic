<?php

namespace App\Models\Concerns;

use App\Models\Auth\User;

trait CreatedBy
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }
}
