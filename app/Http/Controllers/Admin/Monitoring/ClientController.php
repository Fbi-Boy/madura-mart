<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        return view('admin.monitoring.client.index');
    }
}
