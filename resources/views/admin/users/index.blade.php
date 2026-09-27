<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun, role, status akses, dan kredensial pengguna sistem.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm text-gray-800 dark:text-gray-100">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">{{ $errors->first() }}</div>
            @endif

            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <form method="GET" class="grid gap-3 sm:grid-cols-4 lg:flex lg:flex-1">
                    <input name="search" value="{{ $search }}" placeholder="Cari nama atau email..." class="rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <select name="role" class="rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua role</option>
                        @foreach($roles as $item)
                            <option value="{{ $item }}" @selected($role === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                    </select>
                    <button class="rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white dark:bg-[#A8F23A] dark:text-gray-900">Filter</button>
                </form>
                <a href="{{ route('admin.users.create') }}" class="rounded-xl bg-[#A8F23A] px-4 py-2.5 text-center text-sm font-semibold text-gray-900">Tambah User</a>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">
                            <tr><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Role</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold dark:bg-gray-700">{{ $user->role }}</span></td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-[#A8F23A]/20 text-gray-900' : 'bg-red-100 text-red-700' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.users.edit',$user) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold dark:border-gray-600 dark:text-white">Edit</a>
                                            @if(!auth()->user()->is($user))
                                                <form method="POST" action="{{ route('admin.users.status',$user) }}">@csrf @method('PATCH')
                                                    <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold dark:border-gray-600">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
                                                    <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">Belum ada user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())<div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">{{ $users->links() }}</div>@endif
            </div>
        </div>
    </div>
</x-app-layout>