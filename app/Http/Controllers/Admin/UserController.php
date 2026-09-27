<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->orderBy('name');

        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('is_active', $status === 'active');
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'roles' => array_keys(config('permissions.role_labels', [])),
        ]);
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
        unset($data['is_active']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->user()->is($user) && $data['role'] !== $user->role) {
            return back()->withInput()->withErrors([
                'role' => 'Anda tidak dapat mengubah role akun sendiri.',
            ]);
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
            ],
            $request,
        );

        return to_route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
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
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->string('password')->toString())]);

        ActivityLogService::record(
            'user.password_reset',
            "Password user {$user->name} direset oleh Super Admin.",
            $user,
            [],
            $request,
        );

        return to_route('admin.users.edit', $user)->with('success', 'Password user berhasil direset.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');

        if ($user->role === 'super-admin' && User::query()->where('role', 'super-admin')->where('is_active', true)->count() <= 1) {
            return back()->withErrors([
                'user' => 'Minimal satu Super Admin aktif harus tetap tersedia.',
            ]);
        }

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
        ]);
    }
}
