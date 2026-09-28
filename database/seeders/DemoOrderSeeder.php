<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::where('email', 'admin@maduramart.test')->firstOrFail();
            $kurir = User::where('email', 'kurir@maduramart.test')->firstOrFail();
            $customerUser = User::where('email', 'customer@maduramart.test')->firstOrFail();

            $customer = Customer::updateOrCreate(
                ['email' => $customerUser->email],
                [
                    'code' => 'CUS-004', 'name' => 'Customer Demo', 'phone' => '081299998888',
                    'address' => 'Jl. Demo Madura No. 1', 'city' => 'Jember', 'is_active' => true,
                ],
            );

            $owners = Customer::whereIn('code', ['CUS-001', 'CUS-002', 'CUS-004'])->get()->keyBy('code');
            foreach ([
                ['CUS-001', 'Rumah', 'Ahmad Fauzi', '081234567890', 'Jl. Jokotole No. 12', 'Pamekasan', true],
                ['CUS-001', 'Kantor', 'Ahmad Fauzi', '081234567890', 'Jl. Raya Pamekasan No. 20', 'Pamekasan', false],
                ['CUS-002', 'Rumah', 'Nur Aini', '082345678901', 'Jl. Trunojoyo No. 18', 'Sumenep', true],
                ['CUS-004', 'Rumah', 'Customer Demo', '081299998888', 'Jl. Demo Madura No. 1', 'Jember', true],
            ] as [$code, $label, $name, $phone, $address, $city, $default]) {
                DB::table('customer_addresses')->updateOrInsert(
                    ['customer_id' => $owners[$code]->id, 'label' => $label],
                    [
                        'recipient_name' => $name, 'phone' => $phone, 'address' => $address, 'city' => $city,
                        'is_default' => $default, 'created_at' => now(), 'updated_at' => now(),
                    ],
                );
            }

            $products = Product::orderBy('id')->get()->keyBy('sku');
            $courier1 = DB::table('couriers')->where('code', 'KUR-001')->value('id');
            $courier2 = DB::table('couriers')->where('code', 'KUR-002')->value('id');

            $orders = [
                ['MM-DEMO-001', 'CUS-004', null, 'pending', 'pending', 'qris', [['MM-SMB-001', 1], ['MM-MKN-001', 2]]],
                ['MM-DEMO-002', 'CUS-001', $courier1, 'processing', 'paid', 'bank_transfer', [['MM-SMB-002', 2], ['MM-MNM-001', 4]]],
                ['MM-DEMO-003', 'CUS-002', $courier2, 'delivered', 'paid', 'qris', [['MM-SMB-001', 2], ['MM-MNM-001', 5]]],
            ];

            foreach ($orders as $index => [$number, $code, $courierId, $status, $paymentStatus, $paymentMethod, $items]) {
                $owner = $owners[$code];
                $address = $owner->defaultAddress();
                $date = now()->subDays(2 - $index)->setTime(11 + $index, 0);
                $total = collect($items)->sum(fn ($item) => $products[$item[0]]->price * $item[1]);

                $orderId = DB::table('orders')->insertGetId([
                    'order_number' => $number, 'customer_id' => $owner->id, 'courier_id' => $courierId,
                    'order_date' => $date, 'total' => $total, 'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus, 'payment_proof' => $paymentStatus === 'paid' ? 'demo/payment-proof.jpg' : null,
                    'status' => $status, 'delivery_address' => $address?->fullAddress(),
                    'notes' => 'Order demo.', 'delivery_failure_reason' => null, 'payment_rejection_reason' => null,
                    'created_at' => $date, 'updated_at' => $date,
                ]);

                foreach ($items as [$sku, $quantity]) {
                    $product = $products[$sku];
                    DB::table('order_items')->insert([
                        'order_id' => $orderId, 'product_id' => $product->id, 'quantity' => $quantity,
                        'unit_price' => $product->price, 'subtotal' => $product->price * $quantity,
                        'created_at' => $date, 'updated_at' => $date,
                    ]);
                    StockMovementService::apply($product, -$quantity, 'sale', $customerUser, 'order', $orderId, "Penjualan online {$number}");
                }

                $history = [[null, 'pending', 'Pesanan dibuat melalui demo.', $admin->id]];
                if ($status !== 'pending') {
                    $history[] = ['pending', 'processing', 'Pesanan diproses.', $admin->id];
                }
                if ($status === 'delivered') {
                    $history[] = ['processing', 'shipped', 'Pesanan diserahkan ke kurir.', $kurir->id];
                    $history[] = ['shipped', 'delivered', 'Pesanan diterima customer.', $kurir->id];
                }

                foreach ($history as [$from, $to, $note, $userId]) {
                    DB::table('order_status_histories')->insert([
                        'order_id' => $orderId, 'user_id' => $userId, 'from_status' => $from,
                        'to_status' => $to, 'note' => $note, 'created_at' => $date, 'updated_at' => $date,
                    ]);
                }
            }
        });
    }
}
