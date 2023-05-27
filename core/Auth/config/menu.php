<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menu Cache configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [

        /*
         * By default all menus are cached for 24 hours to speed up performance.
         * When menus or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString(env('AUTH_MENU_EXPIRATION', '24 hours')),

        /*
         * The cache key used to store all menus.
         */

        'key' => 'auth:menu:',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */
    'items' => [
        /*
        |--------------------------------------------------------------------------
        | Web App Menu
        |--------------------------------------------------------------------------
        */
        'web' => [
            /*
            |--------------------------------------------------------------------------
            | Home
            |--------------------------------------------------------------------------
            */
            [
                'id' => 'home',
                'name' => 'Home',
                'icon' => null,
                'type' => 'divider',
                'url' => null,
                'gate' => null,
                'sort' => 1,
                'submenus' => [
                    /*
                    |--------------------------------------------------------------------------
                    | Home > Dashboard
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'home-dashboard',
                        'name' => 'Dashboard',
                        'icon' => 'fas fa-home',
                        'type' => 'menu',
                        'url' => '/app',
                        'gate' => null,
                        'sort' => 1,
                    ],

                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Administrative
            |--------------------------------------------------------------------------
            */
            [
                'id' => 'administrative',
                'name' => 'Administrative',
                'description' => 'User can access Administrative menu',
                'icon' => null,
                'type' => 'divider',
                'url' => null,
                'gate' => 'administrative',
                'sort' => 2,
                'submenus' => [
                    /*
                    |--------------------------------------------------------------------------
                    | Administrative > Access
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'administrative-access',
                        'name' => 'Access',
                        'description' => 'User can access Access submenu',
                        'icon' => 'fas fa-user-cog',
                        'type' => 'menu',
                        'url' => null,
                        'gate' => 'administrative:access',
                        'sort' => 1,
                        'submenus' => [
                            /*
                            |--------------------------------------------------------------------------
                            | Administrative > Access > Roles & Permissions
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'administrative-access-role',
                                'name' => 'Roles & Permissions',
                                'description' => 'User can access Roles & Permissions submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/administrative/access/role',
                                'gate' => 'administrative:access:role',
                                'sort' => 1,
                                'permissions' => [
                                    [
                                        'gate' => 'administrative:access:role:create',
                                        'title' => 'Create New Role',
                                        'description' => 'User can create new role',
                                    ],
                                    [
                                        'gate' => 'administrative:access:role:update',
                                        'title' => 'Update Role',
                                        'description' => 'User can modify role',
                                    ],
                                    [
                                        'gate' => 'administrative:access:role:delete',
                                        'title' => 'Delete Role',
                                        'description' => 'User can delete role',
                                    ],
                                    [
                                        'gate' => 'administrative:access:role:assign-permissions',
                                        'title' => 'Assign Permissions',
                                        'description' => 'User can assign permissions to role',
                                    ],
                                ],
                            ],

                            /*
                            |--------------------------------------------------------------------------
                            | Administrative > Access > Admin
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'administrative-access-admin',
                                'name' => 'Admin',
                                'description' => 'User can access Admin submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/administrative/access/admin',
                                'gate' => 'administrative:access:admin',
                                'sort' => 2,
                                'permissions' => [
                                    [
                                        'gate' => 'administrative:access:admin:create',
                                        'title' => 'Create New Admin',
                                        'description' => 'User can create new admin',
                                    ],
                                    [
                                        'gate' => 'administrative:access:admin:update',
                                        'title' => 'Update Admin',
                                        'description' => 'User can modify admin',
                                    ],
                                    [
                                        'gate' => 'administrative:access:admin:delete',
                                        'title' => 'Delete Admin',
                                        'description' => 'User can delete admin',
                                    ],
                                    [
                                        'gate' => 'administrative:access:admin:restore',
                                        'title' => 'Restore Admin',
                                        'description' => 'User can restore admin',
                                    ],
                                ],
                            ],

                            /*
                            |--------------------------------------------------------------------------
                            | Administrative > Access > User
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'administrative-access-user',
                                'name' => 'User',
                                'description' => 'User can access User submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/administrative/access/user',
                                'gate' => 'administrative:access:user',
                                'sort' => 3,
                                'permissions' => [
                                    [
                                        'gate' => 'administrative:access:user:create',
                                        'title' => 'Create New User',
                                        'description' => 'User can create new user',
                                    ],
                                    [
                                        'gate' => 'administrative:access:user:update',
                                        'title' => 'Update User',
                                        'description' => 'User can modify user',
                                    ],
                                    [
                                        'gate' => 'administrative:access:user:delete',
                                        'title' => 'Delete User',
                                        'description' => 'User can delete user',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Master Data
            |--------------------------------------------------------------------------
            */
            [
                'id' => 'master-data',
                'name' => 'Master Data',
                'description' => 'User can access Master Data menu',
                'icon' => null,
                'type' => 'divider',
                'url' => null,
                'gate' => 'master-data',
                'sort' => 3,
                'submenus' => [
                    /*
                    |--------------------------------------------------------------------------
                    | Master Data > Location
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'master-data-location',
                        'name' => 'Location',
                        'description' => 'User can access Location submenu',
                        'icon' => 'fas fa-map-marked',
                        'type' => 'menu',
                        'url' => null,
                        'gate' => 'master-data:location',
                        'sort' => 1,
                        'submenus' => [
                            /*
                            |--------------------------------------------------------------------------
                            | Master Data > Location > Province
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'master-data-location-province',
                                'name' => 'Province',
                                'description' => 'User can access Province submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/master-data/location/province',
                                'gate' => 'master-data:location:province',
                                'sort' => 1,
                                'permissions' => [
                                    [
                                        'gate' => 'master-data:location:province:create',
                                        'title' => 'Create New Province',
                                        'description' => 'User can create new province',
                                    ],
                                    [
                                        'gate' => 'master-data:location:province:update',
                                        'title' => 'Update Province',
                                        'description' => 'User can modify province',
                                    ],
                                    [
                                        'gate' => 'master-data:location:province:delete',
                                        'title' => 'Delete Province',
                                        'description' => 'User can delete province',
                                    ],
                                    [
                                        'gate' => 'master-data:location:province:restore',
                                        'title' => 'Restore Province',
                                        'description' => 'User can restore province',
                                    ],
                                ],
                            ],

                            /*
                            |--------------------------------------------------------------------------
                            | Master Data > Location > City
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'master-data-location-city',
                                'name' => 'City',
                                'description' => 'User can access City submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/master-data/location/city',
                                'gate' => 'master-data:location:city',
                                'sort' => 2,
                                'permissions' => [
                                    [
                                        'gate' => 'master-data:location:city:create',
                                        'title' => 'Create New City',
                                        'description' => 'User can create new city',
                                    ],
                                    [
                                        'gate' => 'master-data:location:city:update',
                                        'title' => 'Update City',
                                        'description' => 'User can modify city',
                                    ],
                                    [
                                        'gate' => 'master-data:location:city:delete',
                                        'title' => 'Delete City',
                                        'description' => 'User can delete city',
                                    ],
                                    [
                                        'gate' => 'master-data:location:city:restore',
                                        'title' => 'Restore City',
                                        'description' => 'User can restore city',
                                    ],
                                ],
                            ],

                            /*
                            |--------------------------------------------------------------------------
                            | Master Data > Location > Subdistrict
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'master-data-location-subdistrict',
                                'name' => 'Subdistrict',
                                'description' => 'User can access Subdistrict submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/master-data/location/subdistrict',
                                'gate' => 'master-data:location:subdistrict',
                                'sort' => 3,
                                'permissions' => [
                                    [
                                        'gate' => 'master-data:location:subdistrict:create',
                                        'title' => 'Create New Subdistrict',
                                        'description' => 'User can create new subdistrict',
                                    ],
                                    [
                                        'gate' => 'master-data:location:subdistrict:update',
                                        'title' => 'Update Subdistrict',
                                        'description' => 'User can modify subdistrict',
                                    ],
                                    [
                                        'gate' => 'master-data:location:subdistrict:delete',
                                        'title' => 'Delete Subdistrict',
                                        'description' => 'User can delete subdistrict',
                                    ],
                                    [
                                        'gate' => 'master-data:location:subdistrict:restore',
                                        'title' => 'Restore Subdistrict',
                                        'description' => 'User can restore subdistrict',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Master Data > Company
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'master-data-company',
                        'name' => 'Company',
                        'description' => 'User can access Company submenu',
                        'icon' => 'fas fa-building',
                        'type' => 'menu',
                        'url' => '/app/master-data/company',
                        'gate' => 'master-data:company',
                        'sort' => 2,
                        'permissions' => [
                            [
                                'gate' => 'master-data:company:create',
                                'title' => 'Create New Company',
                                'description' => 'User can create new company',
                            ],
                            [
                                'gate' => 'master-data:company:update',
                                'title' => 'Update Company',
                                'description' => 'User can modify company',
                            ],
                            [
                                'gate' => 'master-data:company:delete',
                                'title' => 'Delete Company',
                                'description' => 'User can delete company',
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Logistic
            |--------------------------------------------------------------------------
            */
            [
                'id' => 'logistic',
                'name' => 'Logistic',
                'description' => 'User can access Logistic menu',
                'icon' => null,
                'type' => 'divider',
                'url' => null,
                'gate' => 'logistic',
                'sort' => 4,
                'submenus' => [
                    /*
                    |--------------------------------------------------------------------------
                    | Logistic > Trace & Tracking
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'logistic-trace-tracking',
                        'name' => 'Trace & Tracking',
                        'description' => 'User can access Trace & Tracking submenu',
                        'icon' => 'fas fa-map-marker-alt',
                        'type' => 'menu',
                        'url' => null,
                        'gate' => 'logistic:trace-tracking',
                        'sort' => 1,
                        'submenus' => [
                            /*
                            |--------------------------------------------------------------------------
                            | Logistic > Trace & Tracking > Single
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'logistic-trace-tracking-single',
                                'name' => 'Single',
                                'description' => 'User can access Single submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/logistic/trace-tracking/single',
                                'gate' => 'logistic:trace-tracking:single',
                                'sort' => 1,
                            ],

                            /*
                            |--------------------------------------------------------------------------
                            | Logistic > Trace & Tracking > Multiple
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'logistic-trace-tracking-multiple',
                                'name' => 'Multiple',
                                'description' => 'User can access Multiple submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/logistic/trace-tracking/multiple',
                                'gate' => 'logistic:trace-tracking:multiple',
                                'sort' => 2,
                            ],
                        ],
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Logistic > Pickup
                    |--------------------------------------------------------------------------
                    */
                    [
                        'id' => 'logistic-pickup',
                        'name' => 'Pickup',
                        'description' => 'User can access Pickup submenu',
                        'icon' => 'bx bx-receipt',
                        'type' => 'menu',
                        'url' => null,
                        'gate' => 'logistic:pickup',
                        'sort' => 2,
                        'submenus' => [
                            /*
                            |--------------------------------------------------------------------------
                            | Logistic > Pickup > Input Pickup
                            |--------------------------------------------------------------------------
                            */
                            [
                                'id' => 'logistic-pickup-input-pickup',
                                'name' => 'Input Pickup',
                                'description' => 'User can access Input Pickup submenu',
                                'icon' => null,
                                'type' => 'menu',
                                'url' => '/app/logistic/pickup/input-pickup',
                                'gate' => 'logistic:pickup:input-pickup',
                                'sort' => 1,
                                'permissions' => [
                                    [
                                        'gate' => 'logistic:pickup:input-pickup:create',
                                        'title' => 'Create New AWB',
                                        'description' => 'User can create new awb',
                                    ],
                                    [
                                        'gate' => 'logistic:pickup:input-pickup:update',
                                        'title' => 'Update AWB',
                                        'description' => 'User can modify awb',
                                    ],
                                    [
                                        'gate' => 'logistic:pickup:input-pickup:delete',
                                        'title' => 'Delete AWB',
                                        'description' => 'User can delete awb',
                                    ],
                                    [
                                        'gate' => 'logistic:pickup:input-pickup:print',
                                        'title' => 'Print AWB',
                                        'description' => 'User can print awb',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
