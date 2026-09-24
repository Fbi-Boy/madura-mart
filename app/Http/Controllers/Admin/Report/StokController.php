<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StokController extends Controller
{
    public function index(): View
    {
        return view('admin.report.stok.index');
    }
}
