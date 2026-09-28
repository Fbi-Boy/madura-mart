<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $gudang = User::where('email', 'gudang@maduramart.test')->firstOrFail();
            $kasir = User::where('email', 'kasir@maduramart.test')->firstOrFail();
            $purchasing = User::where('email', 'purchasing@maduramart.test')->firstOrFail();
            $products = Product::orderBy('id')->get()->keyBy('sku');

            foreach ($products as $product) {
                $product->update(['stock' => 0]);
            }

            foreach ([
                'MM-SMB-001' => 40,
                'MM-SMB-002' => 60,
                'MM-MNM-001' => 80,
                'MM-MKN-001' => 50,
            ] as $sku => $quantity) {
                StockMovementService::apply($products[$sku], $quantity, 'initial', $gudang, null, null, 'Stok awal demo.');
            }

            $closedShift = DB::table('cashier_shifts')->insertGetId([
                'shift_number' => 'SHIFT-DEMO-001', 'user_id' => $kasir->id,
                'opened_at' => now()->subDays(3)->setTime(8, 0), 'opening_cash' => 500000,
                'closed_at' => now()->subDays(3)->setTime(17, 0), 'closing_cash' => 710000,
                'expected_cash' => 710000, 'closing_notes' => 'Shift demo selesai normal.',
                'status' => 'closed', 'created_at' => now()->subDays(3), 'updated_at' => now()->subDays(3),
            ]);

            DB::table('cashier_shifts')->insert([
                'shift_number' => 'SHIFT-DEMO-002', 'user_id' => $kasir->id,
                'opened_at' => now()->setTime(8, 0), 'opening_cash' => 500000,
                'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
            ]);

            $customerIds = DB::table('customers')->whereIn('code', ['CUS-001', 'CUS-002'])->pluck('id', 'code');

            $sales = [
                ['INV-DEMO-001', $customerIds['CUS-001'], 'cash', [['MM-SMB-001', 2], ['MM-SMB-002', 3]]],
                ['INV-DEMO-002', $customerIds['CUS-002'], 'qris', [['MM-MNM-001', 6], ['MM-MKN-001', 4]]],
                ['INV-DEMO-003', null, 'transfer', [['MM-SMB-001', 1], ['MM-MNM-001', 4]]],
            ];

            $saleIds = [];
            foreach ($sales as [$invoice, $customerId, $method, $items]) {
                $total = collect($items)->sum(fn ($item) => $products[$item[0]]->price * $item[1]);
                $saleDate = now()->subDays($invoice === 'INV-DEMO-003' ? 2 : 3);
                $saleId = DB::table('sales')->insertGetId([
                    'invoice' => $invoice, 'customer_id' => $customerId, 'user_id' => $kasir->id,
                    'shift_id' => $closedShift, 'sale_date' => $saleDate, 'total' => $total,
                    'paid_amount' => $total, 'change_amount' => 0, 'payment_method' => $method,
                    'status' => 'paid', 'notes' => 'Transaksi demo.',
                    'created_at' => $saleDate, 'updated_at' => $saleDate,
                ]);
                $saleIds[$invoice] = $saleId;

                foreach ($items as [$sku, $quantity]) {
                    $product = $products[$sku];
                    DB::table('sale_items')->insert([
                        'sale_id' => $saleId, 'product_id' => $product->id, 'quantity' => $quantity,
                        'unit_price' => $product->price, 'subtotal' => $product->price * $quantity,
                        'created_at' => $saleDate, 'updated_at' => $saleDate,
                    ]);
                    StockMovementService::apply($product, -$quantity, 'sale', $kasir, 'sale', $saleId, "Penjualan {$invoice}");
                }
            }

            $returnItem = DB::table('sale_items')->where('sale_id', $saleIds['INV-DEMO-001'])
                ->where('product_id', $products['MM-SMB-002']->id)->first();

            $returnId = DB::table('sale_returns')->insertGetId([
                'return_number' => 'RET-DEMO-001', 'sale_id' => $saleIds['INV-DEMO-001'],
                'user_id' => $kasir->id, 'return_date' => now()->subDays(2), 'total' => $returnItem->unit_price,
                'refund_method' => 'cash', 'reason' => 'Produk demo dikembalikan customer.',
                'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2),
            ]);

            DB::table('sale_return_items')->insert([
                'sale_return_id' => $returnId, 'sale_item_id' => $returnItem->id, 'quantity' => 1,
                'unit_price' => $returnItem->unit_price, 'subtotal' => $returnItem->unit_price,
                'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2),
            ]);
            StockMovementService::apply($products['MM-SMB-002'], 1, 'return', $kasir, 'sale_return', $returnId, 'Retur demo.');

            $supplier1 = DB::table('suppliers')->where('code', 'SUP-001')->value('id');
            $supplier2 = DB::table('suppliers')->where('code', 'SUP-002')->value('id');

            $purchases = [
                ['PO-DEMO-001', $supplier1, 'received', [['MM-SMB-001', 10, 65000], ['MM-SMB-002', 20, 14000]]],
                ['PO-DEMO-002', $supplier2, 'draft', [['MM-MNM-001', 30, 3500], ['MM-MKN-001', 15, 6500]]],
            ];

            foreach ($purchases as [$invoice, $supplierId, $status, $items]) {
                $submitted = now()->subDays($status === 'received' ? 5 : 0);
                $total = collect($items)->sum(fn ($item) => $item[1] * $item[2]);
                $purchaseId = DB::table('purchases')->insertGetId([
                    'invoice' => $invoice, 'supplier_id' => $supplierId, 'user_id' => $purchasing->id,
                    'purchase_date' => $submitted->toDateString(), 'total' => $total, 'status' => $status,
                    'submitted_at' => $submitted, 'notes' => 'Purchase demo.',
                    'created_at' => $submitted, 'updated_at' => $submitted,
                ]);

                foreach ($items as [$sku, $quantity, $unitPrice]) {
                    DB::table('purchase_items')->insert([
                        'purchase_id' => $purchaseId, 'product_id' => $products[$sku]->id, 'quantity' => $quantity,
                        'received_quantity' => $status === 'received' ? $quantity : 0, 'damaged_quantity' => 0,
                        'unit_price' => $unitPrice, 'subtotal' => $quantity * $unitPrice,
                        'created_at' => $submitted, 'updated_at' => $submitted,
                    ]);
                    if ($status === 'received') {
                        StockMovementService::apply($products[$sku], $quantity, 'purchase_receipt', $gudang, 'purchase', $purchaseId, "Penerimaan {$invoice}");
                    }
                }
            }

            $opnameId = DB::table('stock_opnames')->insertGetId([
                'user_id' => $gudang->id, 'status' => 'completed',
                'notes' => 'Stok opname demo.', 'completed_at' => now()->subDay(),
                'created_at' => now()->subDay(), 'updated_at' => now()->subDay(),
            ]);
            foreach ($products as $product) {
                DB::table('stock_opname_items')->insert([
                    'stock_opname_id' => $opnameId, 'product_id' => $product->id,
                    'system_stock' => $product->stock, 'actual_stock' => $product->stock, 'difference' => 0,
                    'created_at' => now()->subDay(), 'updated_at' => now()->subDay(),
                ]);
            }
        });
    }
}
