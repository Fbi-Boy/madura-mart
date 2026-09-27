<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->is_active === false) {
            abort(403, 'Akun Anda sedang dinonaktifkan.');
        }

        return $next($request);
    }
}
