<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Activity Log</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat perubahan penting yang dicatat oleh sistem.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex w-fit rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Manajemen User</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <form method="GET" class="grid gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-[1fr_1fr_auto]">
                <select name="action" class="rounded-xl border-gray-200 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="">Semua aktivitas</option>
                    @foreach ($actions as $availableAction)
                        <option value="{{ $availableAction }}" @selected($action === $availableAction)>{{ $availableAction }}</option>
                    @endforeach
                </select>
                <select name="user_id" class="rounded-xl border-gray-200 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="0">Semua user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected($userId === $user->id)>{{ $user->name }} · {{ $user->role }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-gray-900">Filter</button>
            </form>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ number_format($logs->total(), 0, ',', '.') }} catatan ditemukan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Waktu</th>
                                <th class="px-5 py-3">Aktor</th>
                                <th class="px-5 py-3">Aktivitas</th>
                                <th class="px-5 py-3">Deskripsi</th>
                                <th class="px-5 py-3">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $log->user?->name ?? 'System' }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $log->user?->role ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ $log->action }}</span>
                                    </td>
                                    <td class="max-w-[520px] px-5 py-4 text-gray-600 dark:text-gray-300">{{ $log->description }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-400">{{ $log->ip_address ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada aktivitas yang tercatat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                        {{ $logs->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
