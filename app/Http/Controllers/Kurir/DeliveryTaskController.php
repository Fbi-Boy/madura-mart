<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DeliveryTaskController extends Controller
{
    public function index(): View
    {
        $courierId = auth()->user()->courier_id ?? null;

        $orders = Order::with(['customer'])
            ->where('courier_id', $courierId)
            ->whereNotIn('status', ['delivered', 'cancelled', 'failed'])
            ->latest('order_date')
            ->paginate(15);

        $pendingCount = Order::where('courier_id', $courierId)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $shippingCount = Order::where('courier_id', $courierId)
            ->where('status', 'shipped')
            ->count();

        return view('kurir.tugas.index', compact('orders', 'pendingCount', 'shippingCount'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->courier_id === (auth()->user()->courier_id ?? null), 403);

        $order->load(['customer', 'items.product', 'statusHistories.user']);

        // ⚠️ PERHATIKAN: view-nya 'kurir.tugas.tugas-show'
        return view('kurir.tugas.tugas-show', compact('order'));
    }
}
