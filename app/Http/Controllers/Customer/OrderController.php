<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();

        $orders = $customer
            ? Order::query()
                ->withCount('items')
                ->with('courier:id,name')
                ->where('customer_id', $customer->id)
                ->latest('order_date')
                ->paginate(10)
                ->withQueryString()
            : Order::query()
                ->whereRaw('1 = 0')
                ->paginate(10);

        return view('customer.orders.index', compact('customer', 'orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();

        abort_unless($customer && $order->customer_id === $customer->id, 404);

        $order->load([
            'items.product:id,name,sku,unit',
            'courier:id,name',
        ]);

        return view('customer.orders.show', compact('order'));
    }
}
