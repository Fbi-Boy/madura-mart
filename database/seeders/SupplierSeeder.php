<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP-001',
                'name' => 'CV Sumber Pangan Madura',
                'contact_person' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'sumberpangan@example.com',
                'address' => 'Jl. Raya Pamekasan No. 10',
                'city' => 'Pamekasan',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-002',
                'name' => 'PT Distribusi Sejahtera',
                'contact_person' => 'Siti Aminah',
                'phone' => '082345678901',
                'email' => 'distribusi@example.com',
                'address' => 'Jl. Trunojoyo No. 25',
                'city' => 'Sumenep',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-003',
                'name' => 'UD Mitra Mart',
                'contact_person' => 'Rudi Haryanto',
                'phone' => '083456789012',
                'email' => 'mitramart@example.com',
                'address' => 'Jl. Panglima Sudirman No. 8',
                'city' => 'Sampang',
                'is_active' => false,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['code' => $supplier['code']],
                $supplier
            );
        }
    }
}
