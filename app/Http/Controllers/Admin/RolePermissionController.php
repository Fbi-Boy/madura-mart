<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $rolePermissions = [];

        foreach (config('permissions.roles', []) as $permission => $allowedRoles) {
            foreach ($allowedRoles as $role) {
                $rolePermissions[$role][] = $permission;
            }
        }

        $roles = collect([
            'super-admin',
            'admin',
            'gudang',
            'kasir',
            'purchasing',
            'kurir',
            'customer',
        ]);

        $permissions = collect(array_keys(config('permissions.roles', [])))
            ->mapWithKeys(fn (string $permission) => [
                $permission => Str::headline(str_replace('.', ' ', $permission)),
            ]);

        return view('admin.roles.permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }
}
