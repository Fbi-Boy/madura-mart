<x-app-layout>
    @php
        // ==== Grup role ====
        $groupConfig = [
            [
                'title'    => 'Akses Manajemen',
                'subtitle' => 'Kontrol atas sistem, konfigurasi, dan laporan.',
                'icon'     => 'shield',
                'accent'   => 'lime',
                'roles'    => ['admin', 'super_admin'],   // Admin kiri, Super Admin kanan
            ],
            [
                'title'    => 'Akses Operasional',
                'subtitle' => 'Aktivitas harian toko: transaksi, pengiriman, dan stok.',
                'icon'     => 'store',
                'accent'   => 'sky',
                'roles'    => ['kasir', 'kurir'],
            ],
        ];

        $lockedRoleKeys = ['super_admin'];   // card ini dapat border lime + badge gembok

        // ---- Normalisasi key role ----
        $normalize = fn ($s) => strtolower(preg_replace('/[\s\-]+/', '_', (string) $s));
        $rolesByNorm = [];
        foreach ($roles as $key => $val) {
            $rolesByNorm[$normalize($key)] = $key;
        }
        $resolveRole = fn ($want) => $rolesByNorm[$normalize($want)] ?? null;

        // ---- Role yang sudah dipetakan ----
        $mappedOriginalKeys = [];
        foreach ($groupConfig as $g) {
            foreach ($g['roles'] as $want) {
                $orig = $resolveRole($want);
                if ($orig !== null) $mappedOriginalKeys[] = $orig;
            }
        }

        // ---- Role sisa ----
        $unassigned = array_values(array_diff(array_keys($roles->toArray()), $mappedOriginalKeys));
        if (! empty($unassigned)) {
            $groupConfig[] = [
                'title'    => 'Role Lainnya',
                'subtitle' => 'Role tambahan yang belum dikelompokkan.',
                'icon'     => 'tag',
                'accent'   => 'zinc',
                'roles'    => $unassigned,
            ];
        }

        $permissionConfig = config('permissions.roles', []);

        $groupIcons = [
            'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z"/>',
            'store'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9l1.5-5h15L21 9M4 9v11h16V9M4 9h16M9 20v-6h6v6"/>',
            'tag'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 12l-8 8-9-9V4h7l10 8zM7.5 7.5h.01"/>',
        ];

        $accentClasses = [
            'lime' => 'bg-[#A8F23A]/20 text-[#365500] dark:text-[#A8F23A]',
            'sky'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300',
            'zinc' => 'bg-black/[0.06] text-black/60 dark:bg-white/[0.08] dark:text-white/60',
        ];
    @endphp

    <div class="space-y-6">

        {{-- ============ HEADER ============ --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">Super Admin</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">Role & Permission</h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">Kelola akses role tanpa mengubah konfigurasi aplikasi secara manual.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">{{ $roles->count() }} role</div>
                <div class="rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs font-semibold text-black/55 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">{{ count($permissions) }} permission</div>
            </div>
        </div>

        {{-- ============ STATUS + INFO + SEARCH ============ --}}
        <div class="grid gap-3 lg:grid-cols-[1fr_1.5fr]">
            <div class="space-y-3">
                <div class="flex h-full items-start gap-3 rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/30 text-sm font-bold text-[#365500] dark:text-[#A8F23A]">⚡</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#4d6800] dark:text-[#A8F23A]">Permission dinamis</p>
                        <p class="mt-1 text-xs leading-5 text-black/55 dark:text-white/55">Semua permission terdaftar ditampilkan. Menyamakan checkbox dengan konfigurasi default menghapus override database.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                <div class="min-w-0 flex-1">
                    <label for="perm-search" class="text-xs font-semibold text-[#171719] dark:text-white">Cari permission</label>
                    <div class="relative mt-2">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-black/30 dark:text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input id="perm-search" type="text" autocomplete="off" placeholder="Ketik nama permission atau label…" class="w-full rounded-xl border-black/[0.08] bg-transparent pl-9 pr-20 text-sm placeholder:text-black/30 focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-white/[0.1] dark:text-white dark:placeholder:text-white/30">
                        <kbd id="perm-search-kbd" class="pointer-events-none absolute right-9 top-1/2 hidden -translate-y-1/2 rounded-md border border-black/10 bg-black/[0.03] px-1.5 py-0.5 text-[10px] font-semibold text-black/45 dark:border-white/10 dark:bg-white/[0.06] dark:text-white/45 sm:inline-block">/</kbd>
                        <button type="button" id="perm-search-clear" aria-label="Bersihkan" class="absolute right-2 top-1/2 hidden h-6 w-6 -translate-y-1/2 items-center justify-center rounded-md text-black/40 transition hover:bg-black/[0.05] hover:text-black/70 dark:text-white/40 dark:hover:bg-white/[0.08] dark:hover:text-white/80">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <button type="button" data-perm-filter="all" class="rounded-full border border-[#A8F23A] bg-[#A8F23A]/15 px-3 py-1 text-[11px] font-semibold text-[#365500] dark:text-[#A8F23A]">Semua</button>
                    <button type="button" data-perm-filter="enabled" class="rounded-full border border-black/[0.08] bg-transparent px-3 py-1 text-[11px] font-semibold text-black/55 transition hover:border-black/20 dark:border-white/[0.1] dark:text-white/55 dark:hover:border-white/25">Aktif</button>
                    <button type="button" data-perm-filter="disabled" class="rounded-full border border-black/[0.08] bg-transparent px-3 py-1 text-[11px] font-semibold text-black/55 transition hover:border-black/20 dark:border-white/[0.1] dark:text-white/55 dark:hover:border-white/25">Nonaktif</button>
                    <button type="button" data-perm-filter="override" class="inline-flex items-center gap-1.5 rounded-full border border-black/[0.08] bg-transparent px-3 py-1 text-[11px] font-semibold text-black/55 transition hover:border-black/20 dark:border-white/[0.1] dark:text-white/55 dark:hover:border-white/25"><span class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>Override</button>
                    <span class="ml-auto text-[11px] font-medium text-black/45 dark:text-white/45"><span id="perm-count">0</span> / {{ count($permissions) }} permission</span>
                </div>
            </div>
        </div>

        {{-- ============ MAIN FORM ============ --}}
        <form method="POST" action="{{ route('admin.roles.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            @foreach($groupConfig as $group)
                @php
                    $groupRoleKeys = collect($group['roles'])
                        ->map(fn ($want) => $resolveRole($want))
                        ->filter()
                        ->values();

                    $groupPermissionTotal = $groupRoleKeys->sum(fn ($r) => $roles[$r]['permissions']->count());
                    $needsPlaceholder     = $groupRoleKeys->count() % 2 !== 0;
                @endphp

                @if($groupRoleKeys->isNotEmpty())
                    <div class="space-y-3">

                        {{-- Group Header --}}
                        <div class="flex flex-col gap-3 px-1 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $accentClasses[$group['accent']] ?? $accentClasses['zinc'] }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $groupIcons[$group['icon']] ?? '' !!}</svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-base font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">{{ $group['title'] }}</h2>
                                    <p class="mt-0.5 text-xs text-black/45 dark:text-white/45">{{ $group['subtitle'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">{{ $groupRoleKeys->count() }} role</span>
                                <span class="rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">{{ $groupPermissionTotal }} permission</span>
                            </div>
                        </div>

                        {{-- Role Cards (2 kolom) --}}
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach($groupRoleKeys as $roleKey)
                                @php
                                    $data     = $roles[$roleKey];
                                    $isLocked = in_array($normalize($roleKey), array_map($normalize, $lockedRoleKeys), true);

                                    $enabledCount = 0;
                                    foreach ($data['permissions'] as $p) {
                                        $def = in_array($roleKey, $permissionConfig[$p] ?? [], true);
                                        $ov  = $overrides[$roleKey.'|'.$p] ?? null;
                                        if ($ov ? $ov->enabled : $def) { $enabledCount++; }
                                    }
                                    $totalCount = $data['permissions']->count();
                                    $percent    = $totalCount > 0 ? (int) round(($enabledCount / $totalCount) * 100) : 0;
                                @endphp

                                <section data-role-section
                                         @class([
                                             'flex flex-col overflow-hidden rounded-2xl bg-white shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:bg-white/[0.04] dark:shadow-none',
                                             'border-2 border-[#A8F23A]/40 dark:border-[#A8F23A]/30' => $isLocked,
                                             'border border-black/[0.06] dark:border-white/[0.08]'      => ! $isLocked,
                                         ])>

                                    <div @class([
                                        'flex flex-col gap-4 border-b border-black/[0.06] p-5 dark:border-white/[0.08]',
                                        'bg-gradient-to-r from-[#A8F23A]/15 via-[#A8F23A]/[0.04] to-transparent' => $isLocked,
                                        'bg-gradient-to-r from-[#A8F23A]/10 via-[#A8F23A]/[0.03] to-transparent' => ! $isLocked,
                                    ])>
                                        <div class="flex min-w-0 items-start gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-sm font-bold uppercase text-[#365500] dark:text-[#A8F23A]">
                                                {{ strtoupper(substr($data['label'], 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <p class="text-sm font-semibold text-[#171719] dark:text-white">{{ $data['label'] }}</p>
                                                    @if($isLocked)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#A8F23A]/15 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.08em] text-[#4d6800] dark:text-[#A8F23A]">
                                                            <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                            Terkunci
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="mt-0.5 text-xs leading-5 text-black/45 dark:text-white/45">{{ $data['description'] }}</p>
                                            </div>
                                            <span class="shrink-0 rounded-full bg-black/[0.04] px-2 py-0.5 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                                                <span data-role-visible-count>{{ $totalCount }}</span> perm
                                            </span>
                                        </div>

                                        <div>
                                            <div class="flex items-center justify-between text-[10px] font-semibold">
                                                <span class="text-black/45 dark:text-white/45">{{ $enabledCount }} / {{ $totalCount }} aktif</span>
                                                <span class="text-[#4d6800] dark:text-[#A8F23A]">{{ $percent }}%</span>
                                            </div>
                                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-black/[0.05] dark:bg-white/[0.08]">
                                                <div class="h-full rounded-full bg-[#A8F23A] transition-all" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-1 p-4">
                                        <div class="grid grid-cols-1 gap-1.5 sm:grid-cols-2">
                                            @forelse($data['permissions'] as $permission)
                                                @php
                                                    $label          = $permissions[$permission] ?? $permission;
                                                    $defaultEnabled = in_array($roleKey, $permissionConfig[$permission] ?? [], true);
                                                    $override       = $overrides[$roleKey.'|'.$permission] ?? null;
                                                    $enabled        = $override ? $override->enabled : $defaultEnabled;
                                                @endphp

                                                <label data-perm-item
                                                       data-perm-key="{{ strtolower($permission) }}"
                                                       data-perm-label="{{ strtolower($label) }}"
                                                       data-perm-enabled="{{ $enabled ? '1' : '0' }}"
                                                       data-perm-override="{{ $override ? '1' : '0' }}"
                                                       class="group relative flex cursor-pointer items-center gap-2.5 rounded-xl border border-black/[0.05] bg-white p-2.5 transition hover:border-[#A8F23A]/50 hover:bg-[#A8F23A]/5 dark:border-white/[0.07] dark:bg-white/[0.02] dark:hover:bg-[#A8F23A]/5">
                                                    <input type="hidden" name="permissions[{{ $roleKey }}][{{ $permission }}]" value="0">
                                                    <input type="checkbox" name="permissions[{{ $roleKey }}][{{ $permission }}]" value="1" @checked($enabled) class="peer sr-only">
                                                    <span class="flex h-4.5 w-4.5 shrink-0 items-center justify-center rounded-md border-2 border-black/15 bg-white text-[10px] font-bold text-transparent transition peer-checked:border-[#A8F23A] peer-checked:bg-[#A8F23A] peer-checked:text-gray-900 peer-focus-visible:ring-2 peer-focus-visible:ring-[#A8F23A]/40 dark:border-white/20 dark:bg-transparent">✓</span>
                                                    <span class="min-w-0 flex-1">
                                                        <span class="block truncate text-[11px] font-semibold text-[#171719] dark:text-white">{{ $label }}</span>
                                                        <span class="mt-0.5 block truncate text-[9px] text-black/35 dark:text-white/35">{{ $permission }}</span>
                                                    </span>
                                                    @if($override)
                                                        <span class="shrink-0 rounded-full bg-yellow-50 px-1.5 py-0.5 text-[8px] font-semibold text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300">ovr</span>
                                                    @endif
                                                </label>
                                            @empty
                                                <div class="sm:col-span-2 rounded-xl border border-dashed border-black/[0.08] p-6 text-center text-xs text-black/45 dark:border-white/[0.1] dark:text-white/45">Tidak ada permission yang cocok.</div>
                                            @endforelse
                                        </div>
                                        <div data-role-empty class="hidden rounded-xl border border-dashed border-black/[0.08] p-6 text-center text-xs text-black/45 dark:border-white/[0.1] dark:text-white/45">Tidak ada permission yang cocok dengan filter.</div>
                                    </div>
                                </section>
                            @endforeach

                            {{-- Coming Soon: HANYA muncul kalau grup punya role ganjil --}}
                            @if($needsPlaceholder)
                                <div class="flex min-h-[320px] flex-col overflow-hidden rounded-2xl border-2 border-dashed border-black/[0.1] bg-black/[0.015] dark:border-white/[0.12] dark:bg-white/[0.015]">
                                    <div class="flex flex-1 flex-col items-center justify-center gap-4 p-6 text-center">
                                        <div class="relative">
                                            <div class="absolute inset-0 -z-10 animate-pulse rounded-full bg-[#A8F23A]/30 blur-2xl"></div>
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#A8F23A]/15 text-[#365500] dark:text-[#A8F23A]">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M14 4s5 1 6 6-3 8-8 9l-4-4c1-5 4-8 6-11z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 15l-3 3m1-4l-2 2m6 0l-2 2"/>
                                                    <circle cx="14.5" cy="9.5" r="1.5" stroke-width="1.6"/>
                                                </svg>
                                            </div>
                                            <svg class="absolute -right-2 -top-2 h-4 w-4 text-[#A8F23A]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/></svg>
                                            <svg class="absolute -bottom-1 -left-2.5 h-3.5 w-3.5 text-[#A8F23A]/70" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-[#171719] dark:text-white">Segera hadir</p>
                                            <p class="mx-auto mt-1 max-w-[15rem] text-xs leading-5 text-black/45 dark:text-white/45">Role baru sedang dalam pengembangan. Slot ini akan terisi otomatis.</p>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.08em] text-[#4d6800] dark:text-[#A8F23A]">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[#A8F23A]"></span>Coming soon
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Sticky Action Bar --}}
            <div class="sticky bottom-4 z-10 flex flex-col gap-3 rounded-2xl border border-black/[0.06] bg-white/95 p-4 shadow-lg backdrop-blur dark:border-white/[0.08] dark:bg-gray-900/95 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/20 text-xs font-bold text-[#365500] dark:text-[#A8F23A]">🔒</div>
                    <p class="text-xs leading-5 text-black/45 dark:text-white/45">Baseline Super Admin dikunci agar akses pengendalian sistem tidak dapat terputus dari workspace ini.</p>
                </div>
                <button type="submit" class="shrink-0 rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:opacity-85">Simpan Permission</button>
            </div>
        </form>
    </div>

    {{-- ============ LIVE FILTER SCRIPT ============ --}}
    <script>
        (function () {
            const input    = document.getElementById('perm-search');
            const clearBtn = document.getElementById('perm-search-clear');
            const kbd      = document.getElementById('perm-search-kbd');
            const counter  = document.getElementById('perm-count');
            const chips    = Array.from(document.querySelectorAll('[data-perm-filter]'));
            const items    = Array.from(document.querySelectorAll('[data-perm-item]'));
            const sections = Array.from(document.querySelectorAll('[data-role-section]'));

            if (! input) return;

            let activeFilter = 'all';
            let query = '';

            const CHIP_ON  = ['border-[#A8F23A]', 'bg-[#A8F23A]/15', 'text-[#365500]', 'dark:text-[#A8F23A]'];
            const CHIP_OFF = ['border-black/[0.08]', 'bg-transparent', 'text-black/55', 'dark:border-white/[0.1]', 'dark:text-white/55'];

            function paintChips() {
                chips.forEach(c => {
                    const on = c.dataset.permFilter === activeFilter;
                    CHIP_ON.forEach(cls  => c.classList.toggle(cls, on));
                    CHIP_OFF.forEach(cls => c.classList.toggle(cls, !on));
                });
            }

            function apply() {
                let visibleTotal = 0;
                items.forEach(el => {
                    const key      = el.dataset.permKey   || '';
                    const label    = el.dataset.permLabel || '';
                    const enabled  = el.dataset.permEnabled  === '1';
                    const override = el.dataset.permOverride === '1';
                    const matchQuery = query === '' || key.includes(query) || label.includes(query);
                    let matchFilter  = true;
                    if (activeFilter === 'enabled')  matchFilter = enabled;
                    if (activeFilter === 'disabled') matchFilter = !enabled;
                    if (activeFilter === 'override') matchFilter = override;
                    const show = matchQuery && matchFilter;
                    el.classList.toggle('hidden', !show);
                    if (show) visibleTotal++;
                });
                sections.forEach(sec => {
                    const list  = sec.querySelectorAll('[data-perm-item]');
                    const empty = sec.querySelector('[data-role-empty]');
                    let visible = 0;
                    list.forEach(i => { if (!i.classList.contains('hidden')) visible++; });
                    const badge = sec.querySelector('[data-role-visible-count]');
                    if (badge) badge.textContent = visible;
                    if (empty) empty.classList.toggle('hidden', visible !== 0);
                    sec.classList.toggle('hidden', visible === 0);
                });
                if (counter) counter.textContent = visibleTotal;
                if (clearBtn) { clearBtn.classList.toggle('hidden', query === ''); clearBtn.classList.toggle('flex', query !== ''); }
                if (kbd)      kbd.classList.toggle('hidden', query !== '');
            }

            input.addEventListener('input', e => { query = e.target.value.trim().toLowerCase(); apply(); });
            clearBtn.addEventListener('click', () => { input.value = ''; query = ''; input.focus(); apply(); });
            chips.forEach(c => c.addEventListener('click', () => { activeFilter = c.dataset.permFilter; paintChips(); apply(); }));
            document.addEventListener('keydown', e => {
                const tag = (e.target.tagName || '').toLowerCase();
                if (e.key === '/' && tag !== 'input' && tag !== 'textarea') { e.preventDefault(); input.focus(); }
                if (e.key === 'Escape' && document.activeElement === input) { input.value = ''; query = ''; apply(); input.blur(); }
            });
            paintChips(); apply();
        })();
    </script>

    {{-- ============ SUCCESS MODAL ============ --}}
    @if (session('status'))
        <div id="status-modal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 transition-opacity duration-300 ease-out"
             role="dialog" aria-modal="true" aria-labelledby="status-modal-title">

            {{-- Backdrop --}}
            <div id="status-modal-backdrop"
                 class="absolute inset-0 bg-black/40 backdrop-blur-sm dark:bg-black/60"></div>

            {{-- Card --}}
            <div id="status-modal-card"
                 class="relative w-full max-w-sm scale-95 overflow-hidden rounded-2xl border border-black/[0.06] bg-white p-6 text-center shadow-2xl transition-all duration-300 ease-out dark:border-white/[0.08] dark:bg-gray-900">

                {{-- Close button --}}
                <button type="button" id="status-modal-close" aria-label="Tutup"
                        class="absolute right-3 top-3 flex h-7 w-7 items-center justify-center rounded-lg text-black/40 transition hover:bg-black/[0.05] hover:text-black/70 dark:text-white/40 dark:hover:bg-white/[0.08] dark:hover:text-white/80">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Success icon --}}
                <div class="relative mx-auto mt-1 flex h-20 w-20 items-center justify-center">
                    <div class="absolute inset-0 -z-10 animate-pulse rounded-full bg-[#A8F23A]/30 blur-2xl"></div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#A8F23A]/20">
                        <svg class="h-8 w-8 text-[#365500] dark:text-[#A8F23A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path class="status-check-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <svg class="absolute -right-1 -top-1 h-5 w-5 text-[#A8F23A]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/>
                    </svg>
                    <svg class="absolute -bottom-1 -left-2 h-4 w-4 text-[#A8F23A]/70" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/>
                    </svg>
                </div>

                {{-- Text --}}
                <h3 id="status-modal-title"
                    class="mt-5 text-base font-semibold tracking-[-0.02em] text-[#171719] dark:text-white">
                    Berhasil disimpan
                </h3>
                <p class="mx-auto mt-1.5 max-w-[18rem] text-sm leading-5 text-black/50 dark:text-white/50">
                    {{ session('status') }}
                </p>

                {{-- Action --}}
                <button type="button" id="status-modal-ok"
                        class="mt-6 w-full rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:opacity-85">
                    Oke, mengerti
                </button>

                {{-- Progress auto-dismiss --}}
                <div class="mt-4 h-0.5 overflow-hidden rounded-full bg-black/[0.05] dark:bg-white/[0.08]">
                    <div id="status-modal-progress" class="h-full w-full origin-left rounded-full bg-[#A8F23A]"></div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const modal     = document.getElementById('status-modal');
                const card      = document.getElementById('status-modal-card');
                const backdrop  = document.getElementById('status-modal-backdrop');
                const closeBtn  = document.getElementById('status-modal-close');
                const okBtn     = document.getElementById('status-modal-ok');
                const progress  = document.getElementById('status-modal-progress');
                const checkPath = modal.querySelector('.status-check-path');

                if (! modal) return;

                const AUTO_DISMISS_MS = 5000;
                let dismissTimer;

                function open() {
                    modal.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        modal.classList.remove('opacity-0');
                        card.classList.remove('scale-95');
                        card.classList.add('scale-100');
                    });

                    // animated check stroke
                    if (checkPath && checkPath.getTotalLength) {
                        const len = checkPath.getTotalLength();
                        checkPath.style.strokeDasharray  = len;
                        checkPath.style.strokeDashoffset = len;
                        checkPath.getBoundingClientRect(); // force reflow
                        checkPath.style.transition       = 'stroke-dashoffset 500ms ease-out 150ms';
                        checkPath.style.strokeDashoffset = 0;
                    }

                    // progress bar
                    if (progress) {
                        progress.style.transition = 'none';
                        progress.style.transform  = 'scaleX(1)';
                        progress.getBoundingClientRect();
                        progress.style.transition = 'transform ' + AUTO_DISMISS_MS + 'ms linear';
                        progress.style.transform  = 'scaleX(0)';
                    }

                    dismissTimer = setTimeout(close, AUTO_DISMISS_MS);
                }

                function close() {
                    clearTimeout(dismissTimer);
                    modal.classList.add('opacity-0');
                    card.classList.remove('scale-100');
                    card.classList.add('scale-95');
                    setTimeout(() => modal.classList.add('hidden'), 300);
                }

                backdrop.addEventListener('click', close);
                closeBtn.addEventListener('click', close);
                okBtn.addEventListener('click', close);
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && ! modal.classList.contains('hidden')) close();
                });

                requestAnimationFrame(open);
            })();
        </script>
    @endif
</x-app-layout>
