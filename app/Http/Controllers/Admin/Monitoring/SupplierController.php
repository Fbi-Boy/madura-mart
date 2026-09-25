<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $suppliers = Supplier::query()
            ->when($search !== '', fn ($query) => $query
                ->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $inactiveSuppliers = Supplier::where('is_active', false)->count();

        return view('admin.monitoring.supplier.index', compact(
            'suppliers',
            'search',
            'totalSuppliers',
            'activeSuppliers',
            'inactiveSuppliers'
        ));
    }
}
