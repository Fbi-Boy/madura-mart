<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\PermissionOverride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolePermissionController
{
    public function index(Request $request): View
    {
        $roles = config('permissions.role_labels', []);
        $descriptions = config('permissions.role_descriptions', []);
        $permissions = config('permissions.roles', []);
        $permissionLabels = config('permissions.permission_labels', []);
        $overrides = PermissionOverride::query()->get()->keyBy(fn ($item) => $item->role.'|'.$item->permission);
        $permissionQuery = trim((string) $request->query('permission', ''));

        $roleMatrix = collect($roles)->mapWithKeys(function (string $label, string $role) use ($descriptions, $permissions, $permissionLabels, $overrides, $permissionQuery) {
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
                        $needle = strtolower($permissionQuery);

                        return str_contains(strtolower($permission), $needle)
                            || str_contains(strtolower($label), $needle);
                    })
                    ->values(),
                'overrides' => $overrides->filter(fn ($override) => $override->role === $role),
            ]];
        });

        return view('admin.roles.index', [
            'roles' => $roleMatrix,
            'permissions' => $permissionLabels,
            'configuredPermissions' => array_keys($permissions),
            'overrides' => $overrides,
            'permissionQuery' => $permissionQuery,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['boolean'],
        ]);

        $roles = array_keys(config('permissions.role_labels', []));
        $permissionKeys = array_keys(config('permissions.permission_labels', []));
        $submitted = $validated['permissions'] ?? [];

        $changes = [];

        foreach ($roles as $role) {
            foreach ($permissionKeys as $permission) {
                if (! array_key_exists($role, $submitted) || ! array_key_exists($permission, $submitted[$role])) {
                    continue;
                }

                $enabled = (bool) $submitted[$role][$permission];
                $defaultEnabled = in_array($role, config('permissions.roles', [])[$permission] ?? [], true);

                if ($enabled === $defaultEnabled) {
                    $deleted = PermissionOverride::query()
                        ->where('role', $role)
                        ->where('permission', $permission)
                        ->delete();

                    if ($deleted > 0) {
                        $changes[] = [
                            'role' => $role,
                            'permission' => $permission,
                            'enabled' => $enabled,
                            'action' => 'restored_default',
                        ];
                    }

                    continue;
                }

                PermissionOverride::updateOrCreate(
                    ['role' => $role, 'permission' => $permission],
                    ['enabled' => $enabled, 'updated_by' => $request->user()->id],
                );

                $changes[] = [
                    'role' => $role,
                    'permission' => $permission,
                    'enabled' => $enabled,
                    'action' => 'override',
                ];
            }
        }

        if ($changes !== []) {
            ActivityLog::query()->create([
                'user_id' => $request->user()->id,
                'action' => 'permission.updated',
                'description' => 'Permission role diperbarui oleh Super Admin.',
                'metadata' => ['changes' => $changes],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return to_route('admin.roles.index')
            ->with('status', 'Permission role berhasil diperbarui.');
    }
}
