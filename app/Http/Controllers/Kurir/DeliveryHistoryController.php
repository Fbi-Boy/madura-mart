<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DeliveryHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $courier = Courier::query()
            ->where('is_active', true)
            ->where('email', $request->user()->email)
            ->first();

        $orders = Order::query()
            ->when($courier, fn ($query) => $query->where('courier_id', $courier->id), fn ($query) => $query->whereRaw('1 = 0'))
            ->whereIn('status', ['delivered', 'cancelled'])
            ->with('customer:id,name')
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        return view('kurir.riwayat', compact('courier', 'orders'));
    }
}
