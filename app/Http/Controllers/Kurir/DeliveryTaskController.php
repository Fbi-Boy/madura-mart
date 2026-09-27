<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DeliveryTaskController extends Controller
{
    private function courier(Request $request): Courier
    {
        return Courier::query()
            ->where('is_active', true)
            ->where('email', $request->user()->email)
            ->firstOrFail();
    }

    public function index(Request $request): View
    {
        $courier = $this->courier($request);
        $status = $request->string('status')->toString();

        $orders = Order::query()
            ->where('courier_id', $courier->id)
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->when(
                in_array($status, ['pending', 'processing', 'shipped'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->with('customer:id,name,phone')
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'all' => Order::query()->where('courier_id', $courier->id)->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
            'pending' => Order::query()->where('courier_id', $courier->id)->where('status', 'pending')->count(),
            'processing' => Order::query()->where('courier_id', $courier->id)->where('status', 'processing')->count(),
            'shipped' => Order::query()->where('courier_id', $courier->id)->where('status', 'shipped')->count(),
        ];

        return view('kurir.tugas', compact('courier', 'orders', 'summary', 'status'));
    }

    public function show(Request $request, Order $order): View
    {
        $courier = $this->courier($request);

        abort_unless($order->courier_id === $courier->id, 403);

        $order->load([
            'customer:id,name,phone,email,address,city',
            'items.product:id,name,sku,unit',
        ]);

        return view('kurir.tugas-show', compact('courier', 'order'));
    }
}
