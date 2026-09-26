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

        $roleMatrix = collect($roles)->mapWithKeys(function (string $label, string $role) use ($descriptions, $permissions) {
            return [$role => [
                'label' => $label,
                'description' => $descriptions[$role] ?? '',
                'permissions' => collect($permissions)
                    ->filter(fn (array $allowedRoles) => in_array($role, $allowedRoles, true))
                    ->keys()
                    ->values(),
            ]];
        });

        return view('admin.roles.index', [
            'roles' => $roleMatrix,
            'permissions' => $permissionLabels,
        ]);
    }
}
