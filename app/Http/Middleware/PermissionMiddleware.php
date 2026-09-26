<?php

namespace App\Http\Middleware;

use App\Models\PermissionOverride;
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

        $overrides = PermissionOverride::query()
            ->where('role', $user->role)
            ->get()
            ->keyBy('permission');

        foreach ($permissions as $permission) {
            $allowedRoles = config('permissions.roles', [])[$permission] ?? [];
            $defaultAllowed = in_array($user->role, $allowedRoles, true);

            if ($overrides->has($permission)) {
                if ($overrides[$permission]->enabled) {
                    return $next($request);
                }

                continue;
            }

            if ($defaultAllowed) {
                return $next($request);
            }
        }

        abort(403);
    }
}
