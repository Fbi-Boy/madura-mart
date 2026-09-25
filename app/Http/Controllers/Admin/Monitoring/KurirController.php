<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KurirController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $couriers = Courier::query()
            ->when($search !== '', fn ($query) => $query
                ->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('vehicle_number', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalCouriers = Courier::count();
        $activeCouriers = Courier::where('is_active', true)->count();
        $inactiveCouriers = Courier::where('is_active', false)->count();

        return view('admin.monitoring.kurir.index', compact(
            'couriers',
            'search',
            'totalCouriers',
            'activeCouriers',
            'inactiveCouriers'
        ));
    }
}
