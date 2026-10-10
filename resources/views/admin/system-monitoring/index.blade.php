<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Super Admin
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Monitoring Sistem
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Pantau kesehatan komponen utama aplikasi Madura Mart.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                    {{ $healthyChecks }}/{{ count($checks) }} sehat
                </div>
                <div class="rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs font-semibold text-black/55 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- ============ HEALTH CHECKS ============ --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($checks as $check)
                    @php
                        $isOk = ($check['status'] ?? null) === 'ok';

                        // Ikon & label tambahan sesuai jenis check (fallback generic)
                        $labelLower = strtolower($check['label'] ?? '');
                        $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5v14"/>'; // fallback
                        if (str_contains($labelLower, 'database') || str_contains($labelLower, 'db')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7c0 1.657 3.582 3 8 3s8-1.343 8-3-3.582-3-8-3-8 1.343-8 3zm0 0v10c0 1.657 3.582 3 8 3s8-1.343 8-3V7M4 12c0 1.657 3.582 3 8 3s8-1.343 8-3"/>';
                        } elseif (str_contains($labelLower, 'storage') || str_contains($labelLower, 'disk') || str_contains($labelLower, 'file')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>';
                        } elseif (str_contains($labelLower, 'cache')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>';
                        } elseif (str_contains($labelLower, 'queue') || str_contains($labelLower, 'job')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h10"/>';
                        } elseif (str_contains($labelLower, 'mail') || str_contains($labelLower, 'email')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>';
                        } elseif (str_contains($labelLower, 'scheduler') || str_contains($labelLower, 'cron')) {
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        }
                    @endphp

                    <section class="flex flex-col overflow-hidden rounded-2xl border bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:bg-white/[0.04] dark:shadow-none
                                     {{ $isOk ? 'border-black/[0.06] dark:border-white/[0.08]' : 'border-red-200 dark:border-red-500/30' }}">

                        <div class="flex items-start justify-between gap-3 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                             {{ $isOk ? 'bg-[#A8F23A]/20 text-[#365500] dark:text-[#A8F23A]' : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $iconPath !!}
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#171719] dark:text-white">
                                        {{ $check['label'] }}
                                    </p>
                                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.08em]
                                               {{ $isOk ? 'text-[#4d6800] dark:text-[#A8F23A]' : 'text-red-600 dark:text-red-300' }}">
                                        {{ $isOk ? 'Healthy' : 'Error' }}
                                    </p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]
                                         {{ $isOk ? 'bg-[#A8F23A]/15 text-[#365500] dark:text-[#A8F23A]' : 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-300' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isOk ? 'bg-[#A8F23A]' : 'bg-red-500' }}"></span>
                                {{ $isOk ? 'OK' : 'Fail' }}
                            </span>
                        </div>

                        <div class="flex-1 px-5 pb-5">
                            <p class="text-xs leading-5 text-black/55 dark:text-white/55">
                                {{ $check['message'] }}
                            </p>
                        </div>
                    </section>
                @endforeach
            </div>

            {{-- ============ MAIN GRID ============ --}}
            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">

                {{-- -------- Recent Activities -------- --}}
                <section class="overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">

                    <div class="flex flex-col gap-3 border-b border-black/[0.06] px-5 py-4 dark:border-white/[0.08] sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-[#365500] dark:text-[#A8F23A]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Aktivitas Sistem Terbaru</h3>
                                <p class="mt-0.5 text-xs text-black/45 dark:text-white/45">Aktivitas yang dicatat oleh audit/activity log.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.activity-logs.index') }}"
                           class="inline-flex items-center gap-1 rounded-full border border-black/[0.06] bg-white px-2.5 py-1 text-[10px] font-semibold text-black/55 transition hover:border-[#A8F23A]/50 hover:text-black/80 dark:border-white/[0.08] dark:bg-white/[0.03] dark:text-white/55 dark:hover:text-white/80">
                            Lihat semua
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                        @forelse ($recentActivities as $activity)
                            @php
                                $actionLower = strtolower((string) $activity->action);
                                $badgeClass = 'bg-[#A8F23A]/20 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]';
                                if (str_contains($actionLower, 'delete') || str_contains($actionLower, 'hapus') || str_contains($actionLower, 'remove')) {
                                    $badgeClass = 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-300';
                                } elseif (str_contains($actionLower, 'create') || str_contains($actionLower, 'tambah') || str_contains($actionLower, 'store') || str_contains($actionLower, 'add')) {
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300';
                                } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'ubah') || str_contains($actionLower, 'change')) {
                                    $badgeClass = 'bg-sky-50 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300';
                                } elseif (str_contains($actionLower, 'login') || str_contains($actionLower, 'logout') || str_contains($actionLower, 'auth')) {
                                    $badgeClass = 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300';
                                }
                            @endphp

                            <div class="flex items-start gap-3 px-5 py-4 transition hover:bg-[#A8F23A]/5 dark:hover:bg-[#A8F23A]/[0.04]">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/20 text-[11px] font-bold uppercase text-[#365500] dark:text-[#A8F23A]">
                                    {{ strtoupper(substr($activity->user?->name ?? 'S', 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $badgeClass }}">
                                                {{ $activity->action }}
                                            </span>
                                            <span class="text-[10px] text-black/35 dark:text-white/35">
                                                · {{ $activity->user?->name ?? 'System' }}
                                                ({{ $activity->user?->role ?? '-' }})
                                            </span>
                                        </div>
                                        <span class="shrink-0 text-[10px] text-black/40 dark:text-white/40">
                                            {{ $activity->created_at?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="mt-1.5 line-clamp-2 text-xs leading-5 text-black/60 dark:text-white/60">
                                        {{ $activity->description }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-12">
                                <div class="mx-auto flex max-w-xs flex-col items-center justify-center text-center">
                                    <div class="relative">
                                        <div class="absolute inset-0 -z-10 animate-pulse rounded-full bg-[#A8F23A]/30 blur-2xl"></div>
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#A8F23A]/15 text-[#365500] dark:text-[#A8F23A]">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-[#171719] dark:text-white">Belum ada aktivitas</p>
                                    <p class="mt-1 text-xs leading-5 text-black/45 dark:text-white/45">
                                        Aktivitas sistem akan otomatis muncul di sini.
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- -------- Right Column -------- --}}
                <section class="space-y-4">

                    {{-- Today activity --}}
                    <div class="rounded-2xl border border-black/[0.06] bg-white p-5 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Aktivitas hari ini</p>
                                <p class="text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                                    {{ number_format($todayActivityCount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-black/45 dark:text-white/45">
                            catatan activity log tercatat hari ini
                        </p>
                    </div>

                    {{-- Operational status --}}
                    <div class="flex items-start gap-3 rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/30 text-[#365500] dark:text-[#A8F23A]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#4d6800] dark:text-[#A8F23A]">Status Operasional</p>
                            <p class="mt-1 text-xs font-medium leading-5 text-black/70 dark:text-white/70">
                                {{ $healthyChecks === count($checks) ? 'Komponen utama terpantau normal.' : 'Ada komponen yang perlu diperiksa.' }}
                            </p>
                            <p class="mt-1 text-[11px] leading-5 text-black/50 dark:text-white/50">
                                Status ini berasal dari pemeriksaan runtime saat halaman dibuka.
                            </p>
                        </div>
                    </div>

                    {{-- Settings link --}}
                    <a href="{{ route('admin.settings.index') }}"
                       class="group block rounded-2xl border border-black/[0.06] bg-white p-5 shadow-[0_8px_24px_rgba(0,0,0,0.035)] transition hover:border-[#A8F23A]/50 hover:bg-[#A8F23A]/5 dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none dark:hover:bg-[#A8F23A]/5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-black/[0.06] text-black/60 transition group-hover:bg-[#A8F23A]/25 group-hover:text-[#365500] dark:bg-white/[0.08] dark:text-white/60 dark:group-hover:text-[#A8F23A]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-[#171719] dark:text-white">Pengaturan Sistem</p>
                                <p class="mt-0.5 text-xs text-black/45 dark:text-white/45">Kelola identitas dan default operasional.</p>
                            </div>
                            <svg class="h-4 w-4 shrink-0 text-black/30 transition group-hover:translate-x-0.5 group-hover:text-[#4d6800] dark:text-white/30 dark:group-hover:text-[#A8F23A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
