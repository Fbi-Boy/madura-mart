<?php

return [
    'roles' => [
        'dashboard.view' => [
            'admin', 'super-admin', 'gudang', 'kasir', 'purchasing', 'kurir', 'customer',
        ],
        'activity-log.view' => ['admin', 'super-admin'],
        'role-permission.view' => ['super-admin'],
        'system-settings.view' => ['admin', 'super-admin'],
        'system-settings.update' => ['admin', 'super-admin'],
        'system-monitoring.view' => ['admin', 'super-admin'],
        'reports.view' => ['admin', 'super-admin'],
        'products.manage' => ['admin', 'super-admin'],
        'suppliers.manage' => ['admin', 'super-admin', 'purchasing'],
        'purchases.manage' => ['admin', 'super-admin', 'purchasing'],
        'stock.manage' => ['admin', 'super-admin', 'gudang'],
        'sales.manage' => ['admin', 'super-admin', 'kasir'],
        'deliveries.manage' => ['admin', 'super-admin', 'kurir'],
        'orders.manage' => ['admin', 'super-admin', 'customer'],
    ],
];
