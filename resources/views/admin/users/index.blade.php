<x-app-layout>
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Super Admin</p>
                <h1 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">User & Staff</h1>
                <p class="mt-1 text-sm text-black/50 dark:text-white/50">Kelola akun, role, status akses, dan reset password pengguna.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.activity-logs.index') }}" class="rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-semibold dark:border-white/10 dark:bg-white/[0.04] dark:text-white">Activity Log</a>
                <a href="{{ route('admin.users.create') }}" class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white dark:bg-[#A8F23A] dark:text-black">Tambah User</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-4 py-3 text-sm text-[#4d6800] dark:text-[#A8F23A]">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-black/[0.02] dark:border-white/10 dark:bg-white/[0.02]">
                        <tr>
                            <th class="px-5 py-3">Nama</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Role</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-5 py-4 font-medium text-[#171719] dark:text-white">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-black/55 dark:text-white/55">{{ $user->email }}</td>
                                <td class="px-5 py-4"><span class="rounded-full bg-black/5 px-2.5 py-1 text-xs font-semibold dark:bg-white/10 dark:text-white">{{ $user->role }}</span></td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-[#A8F23A]/20 text-[#4d6800] dark:text-[#A8F23A]' : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('admin.users.edit',$user) }}" class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-semibold dark:border-white/10 dark:text-white">Edit</a>
                                        @if(!$user->is(auth()->user()))
                                            <form method="POST" action="{{ route('admin.users.toggle-active',$user) }}">@csrf @method('PATCH')
                                                <button class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-semibold dark:border-white/10 dark:text-white">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.reset-password',$user) }}" onsubmit="return confirm('Reset password user ini?')">@csrf
                                                <button class="rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 dark:border-amber-500/30 dark:text-amber-300">Reset Password</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
                                                <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-black/45 dark:text-white/40">Belum ada user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())<div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $users->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>