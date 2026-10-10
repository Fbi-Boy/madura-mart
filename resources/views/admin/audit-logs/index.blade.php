<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Super Admin
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Audit Log
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Jejak perubahan data yang memiliki objek terkait untuk kebutuhan pemeriksaan sistem.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex items-center gap-2 rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#A8F23A] opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-[#A8F23A]"></span>
                    </span>
                    {{ number_format($logs->total(), 0, ',', '.') }} catatan audit
                </div>
                <a href="{{ route('admin.activity-logs.index') }}"
                   class="inline-flex w-fit items-center gap-1.5 rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs font-semibold text-black/55 transition hover:border-black/20 hover:text-black/80 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55 dark:hover:border-white/25 dark:hover:text-white/80">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activity Log
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- ============================================
                 KOTAK 1 — AUDIT SCOPE (full width, di paling atas)
            ============================================ --}}
            <section class="relative overflow-hidden rounded-2xl border border-[#A8F23A]/40 bg-gradient-to-br from-[#A8F23A]/15 via-[#A8F23A]/[0.05] to-transparent p-5 dark:border-[#A8F23A]/30">
                {{-- decorative orbs --}}
                <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-[#A8F23A]/25 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-16 -left-8 h-36 w-36 rounded-full bg-[#A8F23A]/15 blur-3xl"></div>

                {{-- subtle grid pattern --}}
                <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.04] dark:opacity-[0.06]" fill="none">
                    <defs>
                        <pattern id="grid-scope" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
                            <path d="M16 0H0v16" stroke="currentColor" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-scope)"/>
                </svg>

                <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#A8F23A]/30 text-[#365500] ring-1 ring-[#A8F23A]/40 dark:text-[#A8F23A]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">
                                    Audit Scope
                                </h3>
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#A8F23A]/25 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.08em] text-[#365500] dark:text-[#A8F23A]">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Aktif
                                </span>
                            </div>
                            <p class="mt-1 max-w-3xl text-xs leading-5 text-black/55 dark:text-white/55">
                                Halaman ini menampilkan activity log yang memiliki subject/objek data. Metadata ditampilkan apa adanya agar perubahan dapat ditelusuri tanpa mengubah catatan sumber.
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <span class="rounded-full border border-black/[0.06] bg-white/70 px-2.5 py-1 text-[10px] font-semibold text-black/55 backdrop-blur dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">
                            Immutable trail
                        </span>
                        <span class="rounded-full border border-black/[0.06] bg-white/70 px-2.5 py-1 text-[10px] font-semibold text-black/55 backdrop-blur dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">
                            Metadata as-is
                        </span>
                    </div>
                </div>
            </section>

            {{-- ============================================
                 KOTAK 2 — FILTER + 2 MINI CARDS DALAM SATU KOTAK
            ============================================ --}}
            <form method="GET"
                  class="relative overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                {{-- top accent --}}
                <div class="pointer-events-none absolute inset-x-6 top-0 h-px bg-gradient-to-r from-transparent via-[#A8F23A]/60 to-transparent"></div>

                {{-- ========================
                     ROW 1 — 2 mini cards
                ======================== --}}
                <div class="grid gap-3 p-4 pb-0 sm:grid-cols-2">

                    {{-- Mini card: Jenis perubahan --}}
                    <div class="flex items-stretch gap-3 rounded-xl border border-sky-100 bg-gradient-to-br from-sky-50/60 to-transparent p-3 dark:border-sky-500/15 dark:from-sky-500/[0.08] dark:to-transparent">
                        <div class="flex w-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700 ring-1 ring-sky-200/60 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-500/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="flex min-w-0 flex-1 flex-col justify-between py-0.5">
                            <p class="text-[10px] font-bold uppercase tracking-[0.08em] text-black/45 dark:text-white/45">
                                Jenis perubahan
                            </p>
                            <p class="text-2xl font-bold leading-none tracking-[-0.03em] text-[#171719] dark:text-white">
                                {{ count($actions) }}
                            </p>
                        </div>
                    </div>

                    {{-- Mini card: Aktor tercatat --}}
                    <div class="flex items-stretch gap-3 rounded-xl border border-violet-100 bg-gradient-to-br from-violet-50/60 to-transparent p-3 dark:border-violet-500/15 dark:from-violet-500/[0.08] dark:to-transparent">
                        <div class="flex w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 ring-1 ring-violet-200/60 dark:bg-violet-500/15 dark:text-violet-300 dark:ring-violet-500/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-3-3h-2M9 20H4v-2a3 3 0 013-3h2m5 3a4 4 0 10-8 0 4 4 0 008 0zm6-8a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex min-w-0 flex-1 flex-col justify-between py-0.5">
                            <p class="text-[10px] font-bold uppercase tracking-[0.08em] text-black/45 dark:text-white/45">
                                Aktor tercatat
                            </p>
                            <p class="text-2xl font-bold leading-none tracking-[-0.03em] text-[#171719] dark:text-white">
                                {{ count($users) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- divider --}}
                <div class="mx-4 mt-3 border-t border-dashed border-black/[0.08] dark:border-white/[0.08]"></div>

                {{-- ========================
                     ROW 2 — dropdowns + buttons
                ======================== --}}
                <div class="grid gap-3 p-4 sm:grid-cols-[1fr_1fr_auto_auto]">

                    {{-- ===== Custom Select: Perubahan ===== --}}
                    <div class="min-w-0">
                        <label class="text-xs font-semibold text-[#171719] dark:text-white">
                            Perubahan
                        </label>

                        <div class="relative mt-2" data-custom-select>
                            <input type="hidden" name="action" value="{{ $action }}" data-custom-select-value>

                            <button type="button" data-custom-select-trigger
                                    class="group flex h-11 w-full items-center gap-2 rounded-xl border border-black/[0.08] bg-white pl-1.5 pr-1.5 text-left transition hover:border-black/[0.16] focus:border-[#A8F23A] focus:outline-none focus:ring-2 focus:ring-[#A8F23A]/25 dark:border-white/[0.1] dark:bg-white/[0.04] dark:hover:border-white/[0.2] dark:focus:bg-white/[0.06]">

                                <span class="pointer-events-none flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/15 text-[#365500] transition group-focus:bg-[#A8F23A]/25 dark:text-[#A8F23A]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </span>

                                <span data-custom-select-label class="min-w-0 flex-1 truncate text-sm font-medium text-[#171719] dark:text-white">
                                    {{ $action !== '' ? $action : 'Semua perubahan' }}
                                </span>

                                <span class="pointer-events-none flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-black/[0.04] text-black/40 transition group-hover:bg-black/[0.08] dark:bg-white/[0.06] dark:text-white/40 dark:group-hover:bg-white/[0.1]">
                                    <svg data-custom-select-chevron class="h-3.5 w-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </button>

                            <div data-custom-select-panel
                                 class="absolute left-0 right-0 top-full z-40 mt-2 hidden origin-top overflow-hidden rounded-2xl border border-black/[0.06] bg-white opacity-0 -translate-y-1 scale-[0.98] shadow-[0_16px_40px_rgba(0,0,0,0.15)] transition-all duration-150 ease-out dark:border-white/[0.1] dark:bg-gray-900 dark:shadow-[0_16px_40px_rgba(0,0,0,0.6)]">

                                <div class="border-b border-black/[0.06] p-2 dark:border-white/[0.08]">
                                    <div class="relative">
                                        <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-black/35 dark:text-white/35"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" data-custom-select-search
                                               placeholder="Cari perubahan…" autocomplete="off"
                                               class="h-9 w-full rounded-lg border border-transparent bg-black/[0.03] pl-8 pr-3 text-xs text-[#171719] outline-none placeholder:text-black/35 focus:border-[#A8F23A] focus:bg-white focus:ring-2 focus:ring-[#A8F23A]/20 dark:bg-white/[0.06] dark:text-white dark:placeholder:text-white/35 dark:focus:bg-white/[0.08]">
                                    </div>
                                </div>

                                <ul data-custom-select-list class="max-h-64 overflow-y-auto p-1.5">
                                    <li data-custom-select-option data-value=""
                                        @class([
                                            'flex cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-xs transition',
                                            'bg-[#A8F23A]/10 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]' => $action === '',
                                            'text-black/70 hover:bg-black/[0.03] dark:text-white/70 dark:hover:bg-white/[0.06]' => $action !== '',
                                        ])>
                                        <span class="min-w-0 flex-1 truncate font-medium">Semua perubahan</span>
                                        <svg data-option-check class="h-3.5 w-3.5 shrink-0 {{ $action === '' ? 'opacity-100' : 'opacity-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </li>

                                    @foreach ($actions as $availableAction)
                                        <li data-custom-select-option data-value="{{ $availableAction }}"
                                            @class([
                                                'flex cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-xs transition',
                                                'bg-[#A8F23A]/10 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]' => $action === $availableAction,
                                                'text-black/70 hover:bg-black/[0.03] dark:text-white/70 dark:hover:bg-white/[0.06]' => $action !== $availableAction,
                                            ])>
                                            <span class="min-w-0 flex-1 truncate font-medium">{{ $availableAction }}</span>
                                            <svg data-option-check class="h-3.5 w-3.5 shrink-0 {{ $action === $availableAction ? 'opacity-100' : 'opacity-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </li>
                                    @endforeach

                                    <li data-custom-select-empty class="hidden px-3 py-6 text-center text-xs text-black/40 dark:text-white/40">
                                        Tidak ada hasil ditemukan.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Custom Select: Aktor ===== --}}
                    <div class="min-w-0">
                        <label class="text-xs font-semibold text-[#171719] dark:text-white">
                            Aktor
                        </label>

                        @php
                            $activeUser = $userId > 0 ? collect($users)->firstWhere('id', $userId) : null;
                        @endphp

                        <div class="relative mt-2" data-custom-select>
                            <input type="hidden" name="user_id" value="{{ $userId }}" data-custom-select-value>

                            <button type="button" data-custom-select-trigger
                                    class="group flex h-11 w-full items-center gap-2 rounded-xl border border-black/[0.08] bg-white pl-1.5 pr-1.5 text-left transition hover:border-black/[0.16] focus:border-[#A8F23A] focus:outline-none focus:ring-2 focus:ring-[#A8F23A]/25 dark:border-white/[0.1] dark:bg-white/[0.04] dark:hover:border-white/[0.2] dark:focus:bg-white/[0.06]">

                                <span class="pointer-events-none flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 transition group-focus:bg-violet-200 dark:bg-violet-500/15 dark:text-violet-300 dark:group-focus:bg-violet-500/25">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>

                                <span data-custom-select-label class="min-w-0 flex-1 truncate text-sm font-medium text-[#171719] dark:text-white">
                                    {{ $activeUser ? $activeUser->name : 'Semua aktor' }}
                                </span>

                                <span class="pointer-events-none flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-black/[0.04] text-black/40 transition group-hover:bg-black/[0.08] dark:bg-white/[0.06] dark:text-white/40 dark:group-hover:bg-white/[0.1]">
                                    <svg data-custom-select-chevron class="h-3.5 w-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </button>

                            <div data-custom-select-panel
                                 class="absolute left-0 right-0 top-full z-40 mt-2 hidden origin-top overflow-hidden rounded-2xl border border-black/[0.06] bg-white opacity-0 -translate-y-1 scale-[0.98] shadow-[0_16px_40px_rgba(0,0,0,0.15)] transition-all duration-150 ease-out dark:border-white/[0.1] dark:bg-gray-900 dark:shadow-[0_16px_40px_rgba(0,0,0,0.6)]">

                                <div class="border-b border-black/[0.06] p-2 dark:border-white/[0.08]">
                                    <div class="relative">
                                        <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-black/35 dark:text-white/35"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" data-custom-select-search
                                               placeholder="Cari aktor…" autocomplete="off"
                                               class="h-9 w-full rounded-lg border border-transparent bg-black/[0.03] pl-8 pr-3 text-xs text-[#171719] outline-none placeholder:text-black/35 focus:border-[#A8F23A] focus:bg-white focus:ring-2 focus:ring-[#A8F23A]/20 dark:bg-white/[0.06] dark:text-white dark:placeholder:text-white/35 dark:focus:bg-white/[0.08]">
                                    </div>
                                </div>

                                <ul data-custom-select-list class="max-h-64 overflow-y-auto p-1.5">
                                    <li data-custom-select-option data-value="0"
                                        @class([
                                            'flex cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-xs transition',
                                            'bg-[#A8F23A]/10 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]' => $userId === 0,
                                            'text-black/70 hover:bg-black/[0.03] dark:text-white/70 dark:hover:bg-white/[0.06]' => $userId !== 0,
                                        ])>
                                        <span class="min-w-0 flex-1 truncate font-medium">Semua aktor</span>
                                        <svg data-option-check class="h-3.5 w-3.5 shrink-0 {{ $userId === 0 ? 'opacity-100' : 'opacity-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </li>

                                    @foreach ($users as $user)
                                        <li data-custom-select-option data-value="{{ $user->id }}"
                                            @class([
                                                'flex cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-xs transition',
                                                'bg-[#A8F23A]/10 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]' => $userId === $user->id,
                                                'text-black/70 hover:bg-black/[0.03] dark:text-white/70 dark:hover:bg-white/[0.06]' => $userId !== $user->id,
                                            ])>
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/15 text-[10px] font-bold uppercase text-[#365500] dark:bg-[#A8F23A]/20 dark:text-[#A8F23A]">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate font-medium">{{ $user->name }}</span>
                                                <span class="block truncate text-[10px] opacity-60">{{ $user->role }}</span>
                                            </span>
                                            <svg data-option-check class="h-3.5 w-3.5 shrink-0 {{ $userId === $user->id ? 'opacity-100' : 'opacity-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </li>
                                    @endforeach

                                    <li data-custom-select-empty class="hidden px-3 py-6 text-center text-xs text-black/40 dark:text-white/40">
                                        Tidak ada hasil ditemukan.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Filter --}}
                    <div class="flex items-end">
                        <button type="submit"
                                class="group inline-flex h-11 w-full items-center justify-center gap-1.5 rounded-xl bg-[#A8F23A] px-4 text-sm font-semibold text-gray-900 shadow-[0_4px_14px_rgba(168,242,58,0.4)] transition hover:opacity-90 hover:shadow-[0_6px_20px_rgba(168,242,58,0.5)] active:scale-[0.98]">
                            <svg class="h-4 w-4 transition group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>

                    {{-- Reset --}}
                    <div class="flex items-end">
                        @if($action !== '' || $userId > 0)
                            <a href="{{ route('admin.audit-logs.index') }}"
                               class="inline-flex h-11 w-full items-center justify-center gap-1.5 rounded-xl border border-black/[0.08] px-4 text-sm font-semibold text-black/65 transition hover:bg-black/[0.03] dark:border-white/[0.1] dark:text-white/70 dark:hover:bg-white/[0.05]">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        @else
                            <div class="hidden sm:block sm:w-[88px]"></div>
                        @endif
                    </div>
                </div>

                {{-- Active filter chips --}}
                @if($action !== '' || $userId > 0)
                    <div class="flex flex-wrap items-center gap-2 border-t border-black/[0.05] px-4 py-3 dark:border-white/[0.06]">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">
                            Filter aktif:
                        </span>
                        @if($action !== '')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#A8F23A]/15 px-2.5 py-1 text-[10px] font-semibold text-[#365500] dark:text-[#A8F23A]">
                                {{ $action }}
                            </span>
                        @endif
                        @if($userId > 0 && $activeUser)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-100 px-2.5 py-1 text-[10px] font-semibold text-violet-700 dark:bg-violet-500/15 dark:text-violet-300">
                                {{ $activeUser->name }}
                            </span>
                        @endif
                    </div>
                @endif
            </form>

            {{-- ============ TABLE ============ --}}
            <section class="overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">

                <div class="flex flex-col gap-3 border-b border-black/[0.06] px-5 py-4 dark:border-white/[0.08] sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-[#365500] dark:text-[#A8F23A]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Perubahan Terbaru</h3>
                            <p class="mt-0.5 text-xs text-black/45 dark:text-white/45">
                                {{ number_format($logs->total(), 0, ',', '.') }} catatan audit ditemukan.
                            </p>
                        </div>
                    </div>

                    <span class="rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                        Halaman {{ $logs->currentPage() }} / {{ $logs->lastPage() }}
                    </span>
                </div>

                <table class="w-full table-fixed text-left">
                    <colgroup>
                        <col class="w-[14%]">
                        <col class="w-[18%]">
                        <col class="w-[26%]">
                        <col class="w-[15%]">
                        <col class="w-[14%]">
                        <col class="w-[13%]">
                    </colgroup>
                    <thead>
                        <tr class="border-b border-black/[0.06] text-[10px] uppercase tracking-[0.08em] text-black/35 dark:border-white/[0.08] dark:text-white/35">
                            <th class="px-3 py-3 font-semibold">Waktu</th>
                            <th class="px-3 py-3 font-semibold">Aktor</th>
                            <th class="px-3 py-3 font-semibold">Perubahan</th>
                            <th class="px-3 py-3 font-semibold">Objek</th>
                            <th class="px-3 py-3 font-semibold">Metadata</th>
                            <th class="px-3 py-3 font-semibold">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                        @forelse ($logs as $log)
                            @php
                                $actionLower = strtolower((string) $log->action);

                                $style = [
                                    'badge'  => 'bg-[#A8F23A]/20 text-[#365500] dark:bg-[#A8F23A]/15 dark:text-[#A8F23A]',
                                    'accent' => 'bg-[#A8F23A]',
                                ];
                                if (str_contains($actionLower, 'delete') || str_contains($actionLower, 'hapus') || str_contains($actionLower, 'remove')) {
                                    $style = [
                                        'badge'  => 'bg-red-50 text-red-700 ring-1 ring-red-200/60 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-500/20',
                                        'accent' => 'bg-red-400',
                                    ];
                                } elseif (str_contains($actionLower, 'create') || str_contains($actionLower, 'tambah') || str_contains($actionLower, 'store') || str_contains($actionLower, 'add')) {
                                    $style = [
                                        'badge'  => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-500/20',
                                        'accent' => 'bg-emerald-400',
                                    ];
                                } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'ubah') || str_contains($actionLower, 'change')) {
                                    $style = [
                                        'badge'  => 'bg-sky-50 text-sky-700 ring-1 ring-sky-200/60 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-500/20',
                                        'accent' => 'bg-sky-400',
                                    ];
                                }

                                $hasMetadata = is_array($log->metadata) && count($log->metadata) > 0;
                                $metaJson    = $hasMetadata ? json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
                                $metaRowId   = 'meta-row-'.$log->id;
                            @endphp

                            <tr class="group relative transition hover:bg-[#A8F23A]/[0.04] dark:hover:bg-[#A8F23A]/[0.05]">
                                <td class="relative px-3 py-3 align-top">
                                    <span class="absolute inset-y-2 left-0 w-0.5 rounded-r-full {{ $style['accent'] }} opacity-60 transition group-hover:opacity-100"></span>
                                    <div class="pl-1.5">
                                        <p class="text-[11px] font-semibold text-[#171719] dark:text-white">
                                            {{ $log->created_at?->format('d M Y') }}
                                        </p>
                                        <p class="mt-0.5 text-[10px] text-black/40 dark:text-white/40">
                                            {{ $log->created_at?->format('H:i') }}
                                        </p>
                                        <p class="mt-0.5 truncate text-[9px] text-black/35 dark:text-white/35">
                                            {{ $log->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#A8F23A]/40 to-[#A8F23A]/15 text-[10px] font-bold uppercase text-[#365500] ring-1 ring-[#A8F23A]/30 dark:text-[#A8F23A]">
                                            {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-[11px] font-semibold text-[#171719] dark:text-white">
                                                {{ $log->user?->name ?? 'System' }}
                                            </p>
                                            <p class="truncate text-[9px] text-black/40 dark:text-white/40">
                                                {{ $log->user?->role ?? 'Sistem' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-top">
                                    <span class="inline-flex max-w-full items-center gap-1.5 truncate rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $style['badge'] }}">
                                        {{ $log->action }}
                                    </span>
                                    <p class="mt-1.5 line-clamp-2 text-[10px] leading-4 text-black/55 dark:text-white/55">
                                        {{ $log->description }}
                                    </p>
                                </td>

                                <td class="px-3 py-3 align-top">
                                    <div class="inline-flex max-w-full items-center gap-1.5 rounded-md border border-black/[0.06] bg-black/[0.02] px-2 py-1 dark:border-white/[0.08] dark:bg-white/[0.03]">
                                        <svg class="h-3 w-3 shrink-0 text-black/40 dark:text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="truncate text-[10px] font-semibold text-[#171719] dark:text-white">
                                                {{ class_basename($log->subject_type) }}
                                            </p>
                                            <p class="truncate font-mono text-[9px] text-black/40 dark:text-white/40">
                                                #{{ $log->subject_id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-top">
                                    @if($hasMetadata)
                                        <button type="button"
                                                data-meta-toggle="{{ $metaRowId }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-black/[0.08] bg-white px-2 py-1 text-[10px] font-semibold text-black/60 transition hover:border-[#A8F23A]/50 hover:bg-[#A8F23A]/5 hover:text-black/80 dark:border-white/[0.1] dark:bg-white/[0.03] dark:text-white/60 dark:hover:border-[#A8F23A]/40 dark:hover:bg-[#A8F23A]/5 dark:hover:text-white/80">
                                            <svg class="h-3 w-3 transition-transform" data-meta-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                            Lihat
                                            <span class="rounded-full bg-black/[0.06] px-1.5 py-0.5 text-[9px] dark:bg-white/[0.08]">
                                                {{ count($log->metadata) }}
                                            </span>
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-black/[0.03] px-2 py-1 text-[9px] text-black/40 dark:bg-white/[0.05] dark:text-white/40">
                                            <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 align-top">
                                    <span class="inline-block max-w-full truncate rounded-md bg-black/[0.04] px-1.5 py-1 font-mono text-[9px] text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                                        {{ $log->ip_address ?? '—' }}
                                    </span>
                                </td>
                            </tr>

                            @if($hasMetadata)
                                <tr id="{{ $metaRowId }}" class="hidden border-b border-black/[0.05] bg-black/[0.015] dark:border-white/[0.06] dark:bg-white/[0.02]">
                                    <td colspan="6" class="px-3 py-3">
                                        <div class="rounded-xl border border-black/[0.06] bg-white p-3 dark:border-white/[0.08] dark:bg-white/[0.03]">
                                            <div class="mb-2 flex items-center gap-2">
                                                <svg class="h-3 w-3 text-[#4d6800] dark:text-[#A8F23A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                </svg>
                                                <p class="text-[10px] font-bold uppercase tracking-[0.08em] text-black/55 dark:text-white/55">
                                                    Metadata · {{ count($log->metadata) }} field
                                                </p>
                                            </div>
                                            <pre class="max-h-72 overflow-auto rounded-lg bg-black/[0.03] p-3 font-mono text-[10px] leading-5 text-black/75 dark:bg-white/[0.04] dark:text-white/75">{{ $metaJson }}</pre>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16">
                                    <div class="mx-auto flex max-w-sm flex-col items-center justify-center text-center">
                                        <div class="relative">
                                            <div class="absolute inset-0 -z-10 animate-pulse rounded-full bg-[#A8F23A]/30 blur-2xl"></div>
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#A8F23A]/15 text-[#365500] dark:text-[#A8F23A]">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
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
                                            Belum ada perubahan
                                        </p>
                                        <p class="mt-1 text-xs leading-5 text-black/45 dark:text-white/45">
                                            Perubahan data yang memiliki objek terkait akan otomatis tercatat di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($logs->hasPages())
                    <div class="border-t border-black/[0.06] px-5 py-4 dark:border-white/[0.08]">
                        {{ $logs->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    {{-- ============ SCRIPTS ============ --}}
    <script>
        // -------- Custom dropdowns --------
        (function () {
            const SELECTS = document.querySelectorAll('[data-custom-select]');

            function closeAll(except) {
                SELECTS.forEach(root => {
                    if (root === except) return;
                    const panel   = root.querySelector('[data-custom-select-panel]');
                    const chevron = root.querySelector('[data-custom-select-chevron]');
                    if (panel) panel.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                });
            }

            SELECTS.forEach(root => {
                const trigger = root.querySelector('[data-custom-select-trigger]');
                const panel   = root.querySelector('[data-custom-select-panel]');
                const search  = root.querySelector('[data-custom-select-search]');
                const list    = root.querySelector('[data-custom-select-list]');
                const empty   = root.querySelector('[data-custom-select-empty]');
                const hidden  = root.querySelector('[data-custom-select-value]');
                const labelEl = root.querySelector('[data-custom-select-label]');
                const chevron = root.querySelector('[data-custom-select-chevron]');

                if (! trigger || ! panel || ! list || ! hidden || ! labelEl) return;

                const getOptions = () => Array.from(list.querySelectorAll('[data-custom-select-option]'));

                const ACTIVE_CLASSES = ['bg-[#A8F23A]/10', 'text-[#365500]', 'dark:bg-[#A8F23A]/15', 'dark:text-[#A8F23A]'];
                const INACTIVE_CLASSES = ['text-black/70', 'hover:bg-black/[0.03]', 'dark:text-white/70', 'dark:hover:bg-white/[0.06]'];

                function setActive(opt) {
                    getOptions().forEach(o => {
                        const isActive = o === opt;
                        ACTIVE_CLASSES.forEach(c => o.classList.toggle(c, isActive));
                        INACTIVE_CLASSES.forEach(c => o.classList.toggle(c, !isActive));
                        const chk = o.querySelector('[data-option-check]');
                        if (chk) chk.classList.toggle('opacity-0', !isActive);
                    });
                }

                function open() {
                    closeAll(root);
                    panel.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        panel.classList.remove('opacity-0', '-translate-y-1', 'scale-[0.98]');
                    });
                    if (chevron) chevron.classList.add('rotate-180');
                    if (search) {
                        search.value = '';
                        filter('');
                        setTimeout(() => search.focus(), 60);
                    }
                }

                function close() {
                    panel.classList.add('opacity-0', '-translate-y-1', 'scale-[0.98]');
                    if (chevron) chevron.classList.remove('rotate-180');
                    setTimeout(() => panel.classList.add('hidden'), 140);
                }

                function filter(q) {
                    const query = (q || '').trim().toLowerCase();
                    let visible = 0;
                    getOptions().forEach(o => {
                        const text = (o.textContent || '').toLowerCase();
                        const match = query === '' || text.includes(query);
                        o.classList.toggle('hidden', !match);
                        if (match) visible++;
                    });
                    if (empty) empty.classList.toggle('hidden', visible > 0);
                }

                trigger.addEventListener('click', () => {
                    if (panel.classList.contains('hidden')) open(); else close();
                });

                if (search) {
                    search.addEventListener('input', e => filter(e.target.value));
                    search.addEventListener('keydown', e => {
                        if (e.key === 'Escape') { close(); trigger.focus(); }
                    });
                }

                getOptions().forEach(opt => {
                    opt.addEventListener('click', () => {
                        const value = opt.getAttribute('data-value') || '';
                        hidden.value = value;

                        const primarySpan = opt.querySelector('span.font-medium, span.block');
                        labelEl.textContent = primarySpan ? primarySpan.textContent.trim() : (opt.textContent || '').trim();

                        setActive(opt);
                        close();
                    });
                });

                const currentValue = hidden.value;
                const matched = getOptions().find(o => (o.getAttribute('data-value') || '') === String(currentValue));
                if (matched) setActive(matched);
            });

            document.addEventListener('click', e => {
                if (! e.target.closest('[data-custom-select]')) closeAll();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeAll();
            });
        })();

        // -------- Metadata toggle --------
        (function () {
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-meta-toggle]');
                if (! btn) return;

                const id      = btn.getAttribute('data-meta-toggle');
                const row     = document.getElementById(id);
                const chevron = btn.querySelector('[data-meta-chevron]');

                if (! row) return;

                const willOpen = row.classList.contains('hidden');
                row.classList.toggle('hidden', ! willOpen);

                if (chevron) {
                    chevron.style.transform = willOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        })();
    </script>
</x-app-layout>
