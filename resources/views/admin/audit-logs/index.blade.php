<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Audit Log</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jejak perubahan data yang memiliki objek terkait untuk kebutuhan pemeriksaan sistem.</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex w-fit rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Activity Log</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-4 dark:bg-[#A8F23A]/5">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Audit scope</p>
                <p class="mt-1 text-xs leading-5 text-gray-600 dark:text-gray-300">
                    Halaman ini menampilkan activity log yang memiliki subject/objek data. Metadata ditampilkan apa adanya agar perubahan dapat ditelusuri tanpa mengubah catatan sumber.
                </p>
            </div>

            <form method="GET" class="grid gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-[1fr_1fr_auto]">
                <select name="action" class="rounded-xl border-gray-200 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="">Semua perubahan</option>
                    @foreach ($actions as $availableAction)
                        <option value="{{ $availableAction }}" @selected($action === $availableAction)>{{ $availableAction }}</option>
                    @endforeach
                </select>
                <select name="user_id" class="rounded-xl border-gray-200 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="0">Semua aktor</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected($userId === $user->id)>{{ $user->name }} · {{ $user->role }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-gray-900">Filter</button>
            </form>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Perubahan Terbaru</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ number_format($logs->total(), 0, ',', '.') }} catatan audit ditemukan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Waktu</th>
                                <th class="px-5 py-3">Aktor</th>
                                <th class="px-5 py-3">Perubahan</th>
                                <th class="px-5 py-3">Objek</th>
                                <th class="px-5 py-3">Detail</th>
                                <th class="px-5 py-3">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($logs as $log)
                                <tr class="align-top hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $log->user?->name ?? 'System' }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $log->user?->role ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ $log->action }}</span>
                                        <p class="mt-2 max-w-xs text-xs text-gray-500 dark:text-gray-400">{{ $log->description }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-200">{{ class_basename($log->subject_type) }}</p>
                                        <p class="mt-1 text-xs text-gray-400">#{{ $log->subject_id }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if (is_array($log->metadata) && count($log->metadata))
                                            <pre class="max-w-sm overflow-x-auto rounded-xl bg-gray-50 p-3 text-[11px] leading-5 text-gray-600 dark:bg-gray-900 dark:text-gray-300">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            <span class="text-xs text-gray-400">Tidak ada metadata.</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-400">{{ $log->ip_address ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada perubahan yang tercatat.</td></tr>
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
