<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function index(): RedirectResponse
    {
        return auth()->check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    }
}
