<?php

namespace DatabaseSeeders;

use AppModelsUser;
use IlluminateDatabaseSeeder;
use IlluminateSupportFacadesHash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@maduramart.test',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin Madura Mart',
                'email' => 'admin@maduramart.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Staff Gudang',
                'email' => 'gudang@maduramart.test',
                'role' => 'gudang',
            ],
            [
                'name' => 'Kasir Madura Mart',
                'email' => 'kasir@maduramart.test',
                'role' => 'kasir',
            ],
            [
                'name' => 'Staff Purchasing',
                'email' => 'purchasing@maduramart.test',
                'role' => 'purchasing',
            ],
            [
                'name' => 'Kurir Madura Mart',
                'email' => 'kurir@maduramart.test',
                'role' => 'kurir',
            ],
            [
                'name' => 'Customer Madura Mart',
                'email' => 'customer@maduramart.test',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            DistributorSeeder::class,
            CourierSeeder::class,
            UnitSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}