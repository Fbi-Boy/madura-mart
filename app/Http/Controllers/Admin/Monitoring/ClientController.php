<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $clients = Customer::query()
            ->when($search !== '', fn ($query) => $query
                ->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalClients = Customer::count();
        $activeClients = Customer::where('is_active', true)->count();
        $inactiveClients = Customer::where('is_active', false)->count();

        return view('admin.monitoring.client.index', compact(
            'clients', 'search', 'totalClients', 'activeClients', 'inactiveClients'
        ));
    }
}
