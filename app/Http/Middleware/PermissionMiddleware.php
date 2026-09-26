<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        foreach ($permissions as $permission) {
            $allowedRoles = config("permissions.roles.{$permission}", []);

            if (in_array($user->role, $allowedRoles, true)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
