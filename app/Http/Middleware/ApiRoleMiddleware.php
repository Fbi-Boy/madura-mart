<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRoleMiddleware
{
    /**
     * Cek role user yang login via token.
     * Pemakaian: middleware('api.role:admin,super-admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Kalau belum login (nggak ada token valid)
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Cek role
        if (!in_array($user->role, $roles, true)) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak punya akses ke resource ini.',
                'your_role' => $user->role,
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}
