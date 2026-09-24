<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KurirController extends Controller
{
    public function index(): View
    {
        return view('admin.monitoring.kurir.index');
    }
}
