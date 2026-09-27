<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        if (! $request->user()->is_active) {
            abort(403, 'Akun Anda sedang dinonaktifkan.');
        }

        if (! in_array($request->user()->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
