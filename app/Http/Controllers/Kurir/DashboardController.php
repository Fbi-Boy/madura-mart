<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $courierId = Auth::id();

        $stats = [
            'total'     => Order::where('courier_id', $courierId)->count(),
            'ongoing'   => Order::where('courier_id', $courierId)
                                ->whereIn('status', ['shipped', 'on_delivery'])
                                ->count(),
            'completed' => Order::where('courier_id', $courierId)
                                ->where('status', 'delivered')
                                ->count(),
            'failed'    => Order::where('courier_id', $courierId)
                                ->where('status', 'failed')
                                ->count(),
        ];

        $tasks = Order::where('courier_id', $courierId)
            ->whereIn('status', ['shipped', 'on_delivery'])
            ->latest()
            ->limit(10)
            ->get();

        return view('kurir.dashboard', compact('stats', 'tasks'));
    }
}
