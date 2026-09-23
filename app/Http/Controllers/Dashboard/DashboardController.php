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
            default => view('dashboard.index'),
        };
    }
}
