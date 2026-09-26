<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class RolePermissionController
{
    public function index(): View
    {
        $roles = config('permissions.role_labels', []);
        $descriptions = config('permissions.role_descriptions', []);
        $permissions = config('permissions.roles', []);
        $permissionLabels = config('permissions.permission_labels', []);
        $permissionQuery = trim((string) request()->query('permission', ''));

        $roleMatrix = collect($roles)->mapWithKeys(function (string $label, string $role) use ($descriptions, $permissions) {
            return [$role => [
                'label' => $label,
                'description' => $descriptions[$role] ?? '',
                'permissions' => collect($permissions)
                    ->filter(fn (array $allowedRoles) => in_array($role, $allowedRoles, true))
                    ->keys()
                    ->filter(function (string $permission) use ($permissionLabels, $permissionQuery): bool {
                        if ($permissionQuery === '') {
                            return true;
                        }

                        $label = $permissionLabels[$permission] ?? $permission;

                        return str_contains(strtolower($permission), strtolower($permissionQuery))
                            || str_contains(strtolower($label), strtolower($permissionQuery));
                    })
                    ->values(),
            ]];
        });

        return view('admin.roles.index', [
            'roles' => $roleMatrix,
            'permissions' => $permissionLabels,
            'permissionQuery' => $permissionQuery,
        ]);
    }
}
