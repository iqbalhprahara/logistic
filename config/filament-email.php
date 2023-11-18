<?php

use App\Filament\AppPanel\Resources\SystemManagement\EmailLogResource;

return [

    'resource' => [
        'class' => EmailLogResource::class,
        'group' => null,
        'sort' => null,
        'default_sort_column' => 'created_at',
        'default_sort_direction' => 'desc',
    ],

    'keep_email_for_days' => 60,
    'label' => null,
];
