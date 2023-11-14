<?php

use App\Filament\AppPanel\Pages;
use App\Filament\AppPanel\Resources;

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
        | App  Panel Menu
        |--------------------------------------------------------------------------
        */
        'app_panel' => [
            [
                'id' => 'home',
                'name' => 'Home',
                'description' => 'User can access Home',
                'type' => 'group',
                'collapsible' => false,
                'submenus' => [
                    [
                        'id' => 'home-dashboard',
                        'name' => 'Dashboard',
                        'type' => 'page',
                        'page' => Pages\Dashboard::class,
                        'gate' => null,
                    ],
                ],
            ],
            [
                'id' => 'user-management',
                'name' => 'User Management',
                'description' => 'User can access User Management',
                'type' => 'group',
                'collapsible' => true,
                'submenus' => [
                    [
                        'id' => 'user-management-role',
                        'name' => 'Roles & Permissions',
                        'description' => 'User can access Roles & Permissions submenu',
                        'resource' => Resources\UserManagement\RoleResource::class,
                        'type' => 'resource',
                        'gate' => 'user-management:role',
                        'permissions' => [
                            [
                                'gate' => 'user-management:role:create',
                                'title' => 'Create New Role',
                                'description' => 'User can create new role',
                            ],
                            [
                                'gate' => 'user-management:role:update',
                                'title' => 'Update Role',
                                'description' => 'User can modify role',
                            ],
                            [
                                'gate' => 'user-management:role:delete',
                                'title' => 'Delete Role',
                                'description' => 'User can delete role',
                            ],
                        ],
                    ],
                    [
                        'id' => 'user-management-admin',
                        'name' => 'Admin',
                        'description' => 'User can access Admin submenu',
                        'resource' => Resources\UserManagement\AdminResource::class,
                        'type' => 'resource',
                        'gate' => 'user-management:admin',
                        'permissions' => [
                            [
                                'gate' => 'user-management:admin:create',
                                'title' => 'Create New Admin',
                                'description' => 'User can create new admin',
                            ],
                            [
                                'gate' => 'user-management:admin:update',
                                'title' => 'Update Admin',
                                'description' => 'User can modify admin',
                            ],
                            [
                                'gate' => 'user-management:admin:delete',
                                'title' => 'Delete Admin',
                                'description' => 'User can delete admin',
                            ],
                            [
                                'gate' => 'user-management:admin:restore',
                                'title' => 'Restore Admin',
                                'description' => 'User can restore admin',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'client-management',
                'name' => 'Client Management',
                'description' => 'User can access Client Management',
                'type' => 'group',
                'collapsible' => true,
                'submenus' => [
                    [
                        'id' => 'client-management-company',
                        'name' => 'Company',
                        'description' => 'User can access Company submenu',
                        'resource' => Resources\ClientManagement\CompanyResource::class,
                        'type' => 'resource',
                        'gate' => 'client-management:company',
                        'permissions' => [
                            [
                                'gate' => 'client-management:company:create',
                                'title' => 'Create New Company',
                                'description' => 'User can create new company',
                            ],
                            [
                                'gate' => 'client-management:company:update',
                                'title' => 'Update Company',
                                'description' => 'User can modify company',
                            ],
                            [
                                'gate' => 'client-management:company:delete',
                                'title' => 'Delete Company',
                                'description' => 'User can delete company',
                            ],
                        ],
                    ],
                    [
                        'id' => 'client-management-client',
                        'name' => 'Client',
                        'description' => 'User can access Client submenu',
                        'resource' => Resources\ClientManagement\ClientResource::class,
                        'type' => 'resource',
                        'gate' => 'client-management:client',
                        'permissions' => [
                            [
                                'gate' => 'client-management:client:create',
                                'title' => 'Create New Client',
                                'description' => 'User can create new client',
                            ],
                            [
                                'gate' => 'client-management:client:update',
                                'title' => 'Update Client',
                                'description' => 'User can modify client',
                            ],
                            [
                                'gate' => 'client-management:client:delete',
                                'title' => 'Delete Client',
                                'description' => 'User can delete client',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'master-data',
                'name' => 'Master Data',
                'description' => 'User can access Master Data',
                'type' => 'group',
                'collapsible' => true,
                'submenus' => [
                    [
                        'id' => 'master-data-province',
                        'name' => 'Province',
                        'description' => 'User can access Province submenu',
                        'resource' => Resources\MasterData\ProvinceResource::class,
                        'type' => 'resource',
                        'gate' => 'master-data:province',
                        'permissions' => [
                            [
                                'gate' => 'master-data:province:create',
                                'title' => 'Create New Province',
                                'description' => 'User can create new province',
                            ],
                            [
                                'gate' => 'master-data:province:update',
                                'title' => 'Update Province',
                                'description' => 'User can modify province',
                            ],
                            [
                                'gate' => 'master-data:province:delete',
                                'title' => 'Delete Province',
                                'description' => 'User can delete province',
                            ],
                            [
                                'gate' => 'master-data:province:restore',
                                'title' => 'Restore Province',
                                'description' => 'User can restore province',
                            ],
                        ],
                    ],
                    [
                        'id' => 'master-data-city',
                        'name' => 'City',
                        'description' => 'User can access City submenu',
                        'resource' => Resources\MasterData\CityResource::class,
                        'type' => 'resource',
                        'gate' => 'master-data:city',
                        'permissions' => [
                            [
                                'gate' => 'master-data:city:create',
                                'title' => 'Create New City',
                                'description' => 'User can create new city',
                            ],
                            [
                                'gate' => 'master-data:city:update',
                                'title' => 'Update City',
                                'description' => 'User can modify city',
                            ],
                            [
                                'gate' => 'master-data:city:delete',
                                'title' => 'Delete City',
                                'description' => 'User can delete city',
                            ],
                            [
                                'gate' => 'master-data:city:restore',
                                'title' => 'Restore City',
                                'description' => 'User can restore city',
                            ],
                        ],
                    ],
                    [
                        'id' => 'master-data-subdistrict',
                        'name' => 'Subdistrict',
                        'description' => 'User can access Subdistrict submenu',
                        'resource' => Resources\MasterData\SubdistrictResource::class,
                        'type' => 'resource',
                        'gate' => 'master-data:subdistrict',
                        'permissions' => [
                            [
                                'gate' => 'master-data:subdistrict:create',
                                'title' => 'Create New Subdistrict',
                                'description' => 'User can create new subdistrict',
                            ],
                            [
                                'gate' => 'master-data:subdistrict:update',
                                'title' => 'Update Subdistrict',
                                'description' => 'User can modify subdistrict',
                            ],
                            [
                                'gate' => 'master-data:subdistrict:delete',
                                'title' => 'Delete Subdistrict',
                                'description' => 'User can delete subdistrict',
                            ],
                            [
                                'gate' => 'master-data:subdistrict:restore',
                                'title' => 'Restore Subdistrict',
                                'description' => 'User can restore subdistrict',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'logistic',
                'name' => 'Logistic',
                'description' => 'User can access Logistic',
                'type' => 'group',
                'collapsible' => true,
                'submenus' => [
                    [
                        'id' => 'logistic-input-pickup',
                        'name' => 'Input Pickup',
                        'description' => 'User can access Input Pickup submenu',
                        'type' => 'resource',
                        'resource' => Resources\Logistic\InputPickupResource::class,
                        'gate' => 'logistic:input-pickup',
                        'permissions' => [
                            [
                                'gate' => 'logistic:input-pickup:create',
                                'title' => 'Create New AWB',
                                'description' => 'User can create new awb',
                            ],
                            [
                                'gate' => 'logistic:input-pickup:update',
                                'title' => 'Update AWB',
                                'description' => 'User can modify awb',
                            ],
                            [
                                'gate' => 'logistic:input-pickup:input-status',
                                'title' => 'Input AWB Status',
                                'description' => 'User can input awb status',
                            ],
                            [
                                'gate' => 'logistic:input-pickup:delete',
                                'title' => 'Delete AWB',
                                'description' => 'User can delete awb',
                            ],
                            [
                                'gate' => 'logistic:input-pickup:restore',
                                'title' => 'Restore AWB',
                                'description' => 'User can restore awb',
                            ],
                        ],
                    ],
                    [
                        'id' => 'logistic-import-awb-log',
                        'name' => 'Import AWB Log',
                        'description' => 'User can access Import AWB Log submenu',
                        'type' => 'resource',
                        'resource' => Resources\Logistic\ImportAwbLogResource::class,
                        'gate' => 'logistic:import-awb-log',
                        'permissions' => [
                            //
                        ],
                    ],
                ],
            ],
            [
                'id' => 'system-management',
                'name' => 'System Management',
                'description' => 'User can access System Management',
                'type' => 'group',
                'collapsible' => true,
                'submenus' => [
                    [
                        'id' => 'system-management-system-log',
                        'name' => 'System Log',
                        'description' => 'User can access System Log submenu',
                        'type' => 'page',
                        'page' => Pages\SystemManagement\SystemLog::class,
                        'gate' => 'system-management:system-log',
                        'permissions' => [],
                    ],
                    [
                        'id' => 'system-management-email-log',
                        'name' => 'Email Log',
                        'description' => 'User can access Email Log submenu',
                        'type' => 'resource',
                        'resource' => Resources\SystemManagement\EmailLogResource::class,
                        'gate' => 'system-management:email-log',
                        'permissions' => [],
                    ],
                    [
                        'id' => 'system-management-horizon',
                        'name' => 'Queue Monitoring',
                        'description' => 'User can access Queue Monitoring dashboard',
                        'type' => 'url',
                        'route_name' => 'horizon.index',
                        'gate' => 'system-management:horizon',
                        'icon' => 'heroicon-o-circle-stack',
                        'permissions' => [],
                    ],
                ],
            ],
        ],
    ],
];
