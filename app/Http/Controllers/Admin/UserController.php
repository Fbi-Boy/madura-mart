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

        $user = User::create($data);

        ActivityLogService::record(
            'user.created',
            "User {$user->name} berhasil dibuat dengan role {$user->role}.",
            $user,
            ['role' => $user->role],
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
            ],
            $request,
        );

        return to_route('admin.users.index')->with('success', 'User berhasil diperbarui.');
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
        ]);
    }
}
