<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Monitoring Sistem</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pantau kesehatan komponen utama aplikasi Madura Mart.</p>
            </div>
            <div class="rounded-full bg-[#A8F23A]/20 px-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-[#A8F23A]">
                {{ $healthyChecks }}/{{ count($checks) }} sehat
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($checks as $check)
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $check['label'] }}</h3>
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $check['status'] === 'ok' ? 'bg-[#A8F23A]/20 text-gray-900 dark:text-[#A8F23A]' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                {{ $check['status'] === 'ok' ? 'Healthy' : 'Error' }}
                            </span>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">{{ $check['message'] }}</p>
                    </section>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Aktivitas Sistem Terbaru</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aktivitas yang dicatat oleh audit/activity log.</p>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($recentActivities as $activity)
                            <div class="flex gap-4 px-5 py-4">
                                <div class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-[#A8F23A]"></div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $activity->action }}</p>
                                        <span class="text-[11px] text-gray-400">{{ $activity->created_at?->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $activity->description }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $activity->user?->name ?? 'System' }} · {{ $activity->user?->role ?? '-' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-gray-400">Belum ada aktivitas sistem.</div>
                        @endforelse
                    </div>

                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-gray-700 hover:underline dark:text-[#A8F23A]">Buka Activity Log →</a>
                    </div>
                </section>

                <section class="space-y-4">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Aktivitas Hari Ini</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($todayActivityCount, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">catatan activity log tercatat hari ini</p>
                    </div>

                    <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-[#A8F23A]">Status Operasional</p>
                        <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $healthyChecks === count($checks) ? 'Komponen utama terpantau normal.' : 'Ada komponen yang perlu diperiksa.' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">Status ini berasal dari pemeriksaan runtime saat halaman dibuka.</p>
                    </div>

                    <a href="{{ route('admin.settings.index') }}" class="block rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800">
                        <p class="font-semibold text-gray-900 dark:text-white">Pengaturan Sistem</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kelola identitas dan default operasional.</p>
                    </a>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
