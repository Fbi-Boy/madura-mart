<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DeliveryHistoryController extends Controller
{
    public function index(): View
    {
        $courierId = auth()->user()->courier_id ?? null;

        $orders = Order::with(['customer'])
            ->where('courier_id', $courierId)
            ->whereIn('status', ['delivered', 'cancelled', 'failed'])
            ->latest('updated_at')
            ->paginate(20);

        // ⚠️ PERHATIKAN: view-nya 'kurir.riwayat.index'
        return view('kurir.riwayat.index', compact('orders'));
    }
}
