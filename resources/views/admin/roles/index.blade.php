<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">Super Admin</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">Role & Permission</h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">Peta akses setiap role berdasarkan permission yang aktif di sistem.</p>
            </div>
            <div class="rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                {{ $roles->count() }} role · {{ count($permissions) }} permission
            </div>
        </div>

        <form method="GET" action="{{ route('admin.roles.index') }}" class="rounded-2xl border border-black/[0.06] bg-white p-4 dark:border-white/[0.08] dark:bg-white/[0.04]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="min-w-0 flex-1">
                    <label for="permission-search" class="text-xs font-semibold text-[#171719] dark:text-white">Cari permission</label>
                    <input id="permission-search" name="permission" value="{{ $permissionQuery }}"
                           placeholder="Contoh: laporan, stok, supplier..."
                           class="mt-1.5 h-10 w-full rounded-xl border border-black/10 bg-black/[0.02] px-3 text-sm text-[#171719] outline-none transition focus:border-[#A8F23A] focus:ring-2 focus:ring-[#A8F23A]/20 dark:border-white/10 dark:bg-white/[0.04] dark:text-white">
                </div>
                <button type="submit" class="h-10 rounded-xl bg-[#A8F23A] px-4 text-sm font-semibold text-[#171719] transition hover:opacity-85">Cari</button>
                @if($permissionQuery !== '')
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-black/10 px-4 text-sm font-semibold text-[#171719] dark:border-white/10 dark:text-white">Reset</a>
                @endif
            </div>
            @if($permissionQuery !== '')
                <p class="mt-2 text-xs text-black/45 dark:text-white/45">Filter aktif: <span class="font-semibold">{{ $permissionQuery }}</span></p>
            @endif
        </form>

        <div class="rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-4 text-sm text-[#4d6800] dark:text-[#A8F23A]">
            <p class="font-semibold">Sumber akses</p>
            <p class="mt-1 text-xs leading-5 text-black/55 dark:text-white/55">
                Matriks ini membaca konfigurasi permission aplikasi secara langsung. Perubahan akses tetap dilakukan melalui konfigurasi
                <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">config/permissions.php</code>
                agar perubahan hak akses dapat direview melalui Git.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @foreach($roles as $role => $data)
                <section class="rounded-2xl border border-black/[0.06] bg-white p-5 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#171719] dark:text-white">{{ $data['label'] }}</p>
                            <p class="mt-1 text-xs leading-5 text-black/45 dark:text-white/45">{{ $data['description'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                            {{ $data['permissions']->count() }} akses
                        </span>
                    </div>

                    <div class="mt-5 space-y-2">
                        @forelse($data['permissions'] as $permission)
                            <div class="flex items-center gap-3 rounded-xl bg-black/[0.025] px-3 py-2.5 dark:bg-white/[0.035]">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/20 text-xs font-bold text-[#365500] dark:text-[#A8F23A]">✓</span>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white">{{ $permissions[$permission] ?? $permission }}</p>
                                    <p class="mt-0.5 truncate text-[10px] text-black/35 dark:text-white/35">{{ $permission }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl bg-black/[0.025] px-3 py-4 text-xs text-black/40 dark:bg-white/[0.035] dark:text-white/40">{{ $permissionQuery !== '' ? 'Tidak ada permission yang cocok.' : 'Belum ada permission.' }}</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
