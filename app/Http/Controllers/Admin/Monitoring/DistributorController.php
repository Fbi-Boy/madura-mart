<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistributorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $distributors = Distributor::query()
            ->when($search !== '', fn ($query) => $query
                ->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalDistributors = Distributor::count();
        $activeDistributors = Distributor::where('is_active', true)->count();
        $inactiveDistributors = Distributor::where('is_active', false)->count();

        return view('admin.monitoring.distributor.index', compact(
            'distributors', 'search', 'totalDistributors', 'activeDistributors', 'inactiveDistributors'
        ));
    }
}
