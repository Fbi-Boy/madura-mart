<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => view('admin.dashboard'),
            'kasir' => view('kasir.dashboard'),
            'kurir' => view('kurir.dashboard'),
            'customer' => view('customer.dashboard'),
            'purchasing' => view('purchasing.dashboard'),
            'super-admin' => view('super-admin.dashboard'),

            default => view('dashboard.index'),
        };
    }
}
