<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(): View
    {
        return view('admin.monitoring.pembelian.index');
    }
}
