<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    |
    | Permissions are mapped to the roles that may perform the corresponding
    | action. Keep this map as the application-level source of truth until
    | database-backed permissions are introduced.
    |
    */

    'roles' => [
        'dashboard.view' => [
            'admin',
            'super-admin',
            'gudang',
            'kasir',
            'purchasing',
            'kurir',
            'customer',
        ],

        'activity-log.view' => [
            'admin',
            'super-admin',
        ],

        'reports.view' => [
            'admin',
            'super-admin',
        ],

        'products.manage' => [
            'admin',
            'super-admin',
        ],

        'suppliers.manage' => [
            'admin',
            'super-admin',
            'purchasing',
        ],

        'purchases.manage' => [
            'admin',
            'super-admin',
            'purchasing',
        ],

        'stock.manage' => [
            'admin',
            'super-admin',
            'gudang',
        ],

        'sales.manage' => [
            'admin',
            'super-admin',
            'kasir',
        ],

        'deliveries.manage' => [
            'admin',
            'super-admin',
            'kurir',
        ],

        'orders.manage' => [
            'admin',
            'super-admin',
            'customer',
        ],
    ],
];
