<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        $user = User::create($data);

        ActivityLogService::record(
            'user.created',
            "User {$user->name} berhasil dibuat dengan role {$user->role}.",
            $user,
            ['role' => $user->role, 'is_active' => true],
            $request,
        );

        return to_route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        $oldRole = $user->role;
        $oldActive = $user->is_active;

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        ActivityLogService::record(
            'user.updated',
            "User {$user->name} berhasil diperbarui.",
            $user,
            [
                'role_before' => $oldRole,
                'role_after' => $user->role,
                'role_changed' => $oldRole !== $user->role,
                'is_active_before' => $oldActive,
                'is_active_after' => $user->is_active,
                'status_changed' => $oldActive !== $user->is_active,
            ],
            $request,
        );

        return to_route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');

        $user->update(['is_active' => ! $user->is_active]);

        ActivityLogService::record(
            $user->is_active ? 'user.activated' : 'user.deactivated',
            "User {$user->name} ".($user->is_active ? 'diaktifkan' : 'dinonaktifkan').".",
            $user,
            ['is_active' => $user->is_active],
            $request,
        );

        return to_route('admin.users.index')->with('success', 'Status user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Gunakan fitur ubah password pada profil untuk akun sendiri.');

        $temporaryPassword = Str::password(12);

        $user->update(['password' => Hash::make($temporaryPassword)]);

        ActivityLogService::record(
            'user.password_reset',
            "Password user {$user->name} di-reset oleh Super Admin.",
            $user,
            ['temporary_password_generated' => true],
            $request,
        );

        return to_route('admin.users.index')
            ->with('success', "Password {$user->name} berhasil di-reset. Password sementara: {$temporaryPassword}");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');

        $name = $user->name;
        $role = $user->role;

        ActivityLogService::record(
            'user.deleted',
            "User {$name} akan dihapus dari sistem.",
            $user,
            ['role' => $role],
            $request,
        );

        $user->delete();

        return to_route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $uniqueEmail = 'unique:users,email'.($user ? ','.$user->id : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $uniqueEmail],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,super-admin,gudang,kasir,purchasing,kurir,customer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
