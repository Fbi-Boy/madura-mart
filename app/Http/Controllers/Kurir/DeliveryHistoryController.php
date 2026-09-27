<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
            ->firstOrFail();

        $status = $request->string('status')->toString();
        $search = trim($request->string('q')->toString());

        $baseOrders = Order::query()
            ->where('courier_id', $courier->id)
            ->whereIn('status', ['delivered', 'cancelled']);

        $orders = (clone $baseOrders)
            ->when(
                in_array($status, ['delivered', 'cancelled'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$search}%"));
                });
            })
            ->with('customer:id,name,phone')
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'all' => (clone $baseOrders)->count(),
            'delivered' => (clone $baseOrders)->where('status', 'delivered')->count(),
            'cancelled' => (clone $baseOrders)->where('status', 'cancelled')->count(),
        ];

        $orderIds = (clone $baseOrders)->select('id');

        $recentActivities = ActivityLog::query()
            ->with('user:id,name')
            ->where('action', 'delivery.status_updated')
            ->where('subject_type', Order::class)
            ->whereIn('subject_id', $orderIds)
            ->latest()
            ->limit(8)
            ->get(['id', 'user_id', 'subject_id', 'description', 'metadata', 'created_at']);

        return view('kurir.riwayat', compact(
            'courier',
            'orders',
            'summary',
            'status',
            'search',
            'recentActivities',
        ));
    }
}
