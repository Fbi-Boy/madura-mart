<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSystemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@maduramart.test')->firstOrFail();
        $superAdmin = User::where('email', 'superadmin@maduramart.test')->firstOrFail();
        $gudang = User::where('email', 'gudang@maduramart.test')->firstOrFail();
        $kasir = User::where('email', 'kasir@maduramart.test')->firstOrFail();
        $product = Product::orderBy('id')->firstOrFail();

        foreach ([
            ['store_name', 'Madura Mart', 'text', 'Nama toko.'],
            ['minimum_order', '10000', 'number', 'Minimum nilai checkout customer.'],
            ['payment_methods', 'QRIS, Transfer Bank', 'text', 'Metode pembayaran customer.'],
            ['store_phone', '081200001234', 'text', 'Nomor kontak toko.'],
            ['store_city', 'Jember', 'text', 'Kota operasional toko.'],
        ] as [$key, $value, $type, $description]) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'description' => $description, 'created_at' => now(), 'updated_at' => now()],
            );
        }

        foreach ([
            ['admin', 'reports.view'], ['admin', 'payment.verify'], ['gudang', 'inventory.manage'],
            ['kasir', 'sales.manage'], ['purchasing', 'purchases.manage'], ['kurir', 'deliveries.manage'],
        ] as [$role, $permission]) {
            DB::table('permission_overrides')->updateOrInsert(
                ['role' => $role, 'permission' => $permission],
                ['enabled' => true, 'updated_by' => $superAdmin->id, 'created_at' => now(), 'updated_at' => now()],
            );
        }

        foreach ([
            [$admin->id, 'demo.seeded', 'Demo data transaksi berhasil dibuat.', null, null],
            [$gudang->id, 'inventory.initialized', 'Stok awal demo berhasil dibuat.', Product::class, $product->id],
            [$kasir->id, 'sale.created', 'Transaksi POS demo dibuat.', 'App\\Models\\Sale', null],
        ] as [$userId, $action, $description, $subjectType, $subjectId]) {
            DB::table('activity_logs')->insert([
                'user_id' => $userId, 'action' => $action, 'subject_type' => $subjectType,
                'subject_id' => $subjectId, 'description' => $description,
                'metadata' => json_encode(['source' => 'DemoSystemSeeder']),
                'ip_address' => '127.0.0.1', 'user_agent' => 'MaduraMart Demo Seeder',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
