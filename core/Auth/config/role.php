<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Required Role
    |--------------------------------------------------------------------------
    */
    'required' => [
        'web' => [
            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Super Admin',
                'permissions' => 'all',
            ],

            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Client',
                'permissions' => [
                    'logistic',
                    // 'logistic:trace-tracking',
                    // 'logistic:trace-tracking:single',
                    // 'logistic:trace-tracking:multiple',
                    'logistic:pickup',
                    'logistic:pickup:input-pickup:create',
                    'logistic:pickup:input-pickup:print-awb',
                ],
            ],
        ],
    ],

];
