<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Super Admin
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Activity Log
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Riwayat perubahan penting yang dicatat oleh sistem.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                    {{ number_format($logs->total(), 0, ',', '.') }} catatan
                </div>
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex w-fit items-center gap-1.5 rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs font-semibold text-black/55 transition hover:border-black/20 hover:text-black/80 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55 dark:hover:border-white/25 dark:hover:text-white/80">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-3-3h-2M9 20H4v-2a3 3 0 013-3h2m5 3a4 4 0 10-8 0 4 4 0 008 0zm6-8a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manajemen User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- ============ INFO STRIP ============ --}}
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="flex items-start gap-3 rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/30 text-sm font-bold text-[#365500] dark:text-[#A8F23A]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Total catatan</p>
                        <p class="mt-0.5 text-lg font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">
                            {{ number_format($logs->total(), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Jenis aktivitas</p>
                        <p class="mt-0.5 text-lg font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">
                            {{ count($actions) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-black/[0.06] text-black/60 dark:bg-white/[0.08] dark:text-white/60">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-3-3h-2M9 20H4v-2a3 3 0 013-3h2m5 3a4 4 0 10-8 0 4 4 0 008 0zm6-8a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">User tercatat</p>
                        <p class="mt-0.5 text-lg font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">
                            {{ count($users) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ============ FILTER ============ --}}
            <form method="GET"
                  class="grid gap-3 rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none sm:grid-cols-[1fr_1fr_auto_auto]">

                {{-- Aktivitas --}}
                <div class="min-w-0">
                    <label for="filter-action" class="text-xs font-semibold text-[#171719] dark:text-white">
                        Aktivitas
                    </label>
                    <div class="relative mt-2">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-black/35 dark:text-white/35"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>

                        <select id="filter-action" name="action"
                                class="h-11 w-full appearance-none rounded-xl border border-black/[0.08] bg-black/[0.03] pl-9 pr-9 text-sm text-[#171719] transition focus:border-[#A8F23A] focus:bg-white focus:ring-2 focus:ring-[#A8F23A]/30 dark:border-white/[0.1] dark:bg-white/[0.04] dark:text-white dark:focus:bg-white/[0.06]">
                            <option value="">Semua aktivitas</option>
                            @foreach ($actions as $availableAction)
                                <option value="{{ $availableAction }}" @selected($action === $availableAction)>
                                    {{ $availableAction }}
                                </option>
                            @endforeach
                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-black/35 dark:text-white/35"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- User --}}
                <div class="min-w-0">
                    <label for="filter-user" class="text-xs font-semibold text-[#171719] dark:text-white">
                        User
                    </label>
                    <div class="relative mt-2">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-black/35 dark:text-white/35"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>

                        <select id="filter-user" name="user_id"
                                class="h-11 w-full appearance-none rounded-xl border border-black/[0.08] bg-black/[0.03] pl-9 pr-9 text-sm text-[#171719] transition focus:border-[#A8F23A] focus:bg-white focus:ring-2 focus:ring-[#A8F23A]/30 dark:border-white/[0.1] dark:bg-white/[0.04] dark:text-white dark:focus:bg-white/[0.06]">
                            <option value="0">Semua user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($userId === $user->id)>
                                    {{ $user->name }} · {{ $user->role }}
                                </option>
                            @endforeach
                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-black/35 dark:text-white/35"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end">
                    <button type="submit"
                            class="inline-flex h-11 w-full items-center justify-center gap-1.5 rounded-xl bg-[#A8F23A] px-4 text-sm font-semibold text-gray-900 transition hover:opacity-85">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                </div>

                {{-- Tombol Reset --}}
                <div class="flex items-end">
                    @if($action !== '' || $userId > 0)
                        <a href="{{ route('admin.activity-log.index') }}"
                           class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-black/[0.08] px-4 text-sm font-semibold text-black/65 transition hover:bg-black/[0.03] dark:border-white/[0.1] dark:text-white/70 dark:hover:bg-white/[0.05]">
                            Reset
                        </a>
                    @else
                        <div class="hidden sm:block sm:w-[76px]"></div>
                    @endif
                </div>
            </form>

            {{-- ============ TABLE ============ --}}
            <section class="overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">

                <div class="flex flex-col gap-3 border-b border-black/[0.06] px-5 py-4 dark:border-white/[0.08] sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-[#365500] dark:text-[#A8F23A]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Aktivitas Terbaru</h3>
                            <p class="mt-0.5 text-xs text-black/45 dark:text-white/45">
                                {{ number_format($logs->total(), 0, ',', '.') }} catatan ditemukan.
                            </p>
                        </div>
                    </div>

                    <span class="rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                        Halaman {{ $logs->currentPage() }} / {{ $logs->lastPage() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left">
                        <thead>
                            <tr class="border-b border-black/[0.06] text-[10px] uppercase tracking-[0.08em] text-black/35 dark:border-white/[0.08] dark:text-white/35">
                                <th class="px-5 py-3 font-semibold">Waktu</th>
                                <th class="px-5 py-3 font-semibold">Aktor</th>
                                <th class="px-5 py-3 font-semibold">Aktivitas</th>
                                <th class="px-5 py-3 font-semibold">Deskripsi</th>
                                <th class="px-5 py-3 font-semibold">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                            @forelse ($logs as $log)
                                @php
                                    $actionLower = strtolower((string) $log->action);
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

                                <tr class="transition hover:bg-[#A8F23A]/5 dark:hover:bg-[#A8F23A]/[0.04]">
                                    {{-- Waktu --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white">
                                            {{ $log->created_at?->format('d M Y') }}
                                        </p>
                                        <p class="mt-0.5 text-[10px] text-black/40 dark:text-white/40">
                                            {{ $log->created_at?->format('H:i:s') }}
                                            @if($log->created_at)
                                                · {{ $log->created_at->diffForHumans() }}
                                            @endif
                                        </p>
                                    </td>

                                    {{-- Aktor --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/20 text-[11px] font-bold uppercase text-[#365500] dark:text-[#A8F23A]">
                                                {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">
                                                    {{ $log->user?->name ?? 'System' }}
                                                </p>
                                                <p class="mt-0.5 truncate text-[10px] text-black/40 dark:text-white/40">
                                                    {{ $log->user?->role ?? 'Sistem' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Aktivitas --}}
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $badgeClass }}">
                                            {{ $log->action }}
                                        </span>
                                    </td>

                                    {{-- Deskripsi --}}
                                    <td class="max-w-[520px] px-5 py-4">
                                        <p class="line-clamp-2 text-xs leading-5 text-black/60 dark:text-white/60">
                                            {{ $log->description }}
                                        </p>
                                    </td>

                                    {{-- IP --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-md bg-black/[0.04] px-2 py-1 font-mono text-[10px] text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                                            {{ $log->ip_address ?? '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16">
                                        <div class="mx-auto flex max-w-sm flex-col items-center justify-center text-center">
                                            <div class="relative">
                                                <div class="absolute inset-0 -z-10 animate-pulse rounded-full bg-[#A8F23A]/30 blur-2xl"></div>
                                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#A8F23A]/15 text-[#365500] dark:text-[#A8F23A]">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                    </svg>
                                                </div>
                                                <svg class="absolute -right-2 -top-2 h-4 w-4 text-[#A8F23A]" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/>
                                                </svg>
                                                <svg class="absolute -bottom-1 -left-2.5 h-3.5 w-3.5 text-[#A8F23A]/70" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/>
                                                </svg>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold text-[#171719] dark:text-white">
                                                Belum ada aktivitas
                                            </p>
                                            <p class="mt-1 text-xs leading-5 text-black/45 dark:text-white/45">
                                                Aktivitas akan otomatis muncul di sini begitu sistem mulai mencatat perubahan penting.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div class="border-t border-black/[0.06] px-5 py-4 dark:border-white/[0.08]">
                        {{ $logs->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
