<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">Super Admin</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">Role & Permission</h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">Kelola akses role tanpa mengubah konfigurasi aplikasi secara manual.</p>
            </div>
            <div class="rounded-full border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                {{ $roles->count() }} role · {{ count($permissions) }} permission
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-4 text-sm font-medium text-[#4d6800] dark:text-[#A8F23A]">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-4 text-sm text-[#4d6800] dark:text-[#A8F23A]">
            <p class="font-semibold">Permission dinamis</p>
            <p class="mt-1 text-xs leading-5 text-black/55 dark:text-white/55">
                Checkbox mengikuti permission default dari <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">config/permissions.php</code>.
                Perubahan manual disimpan sebagai override database dan dapat dikembalikan ke nilai default dengan menyamakan checkbox.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.roles.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            @foreach($roles as $role => $data)
                <section class="rounded-2xl border border-black/[0.06] bg-white p-5 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#171719] dark:text-white">{{ $data['label'] }}</p>
                            <p class="mt-1 text-xs leading-5 text-black/45 dark:text-white/45">{{ $data['description'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-semibold text-black/55 dark:bg-white/[0.06] dark:text-white/55">
                            {{ $data['permissions']->count() }} default
                        </span>
                    </div>

                    <div class="mt-5 grid gap-2 md:grid-cols-2">
                        @foreach($permissions as $permission => $label)
                            @php
                                $defaultEnabled = $data['permissions']->contains($permission);
                                $override = $overrides[$role.'|'.$permission] ?? null;
                                $enabled = $override ? $override->enabled : $defaultEnabled;
                            @endphp
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-black/[0.05] px-3 py-3 transition hover:border-[#A8F23A]/50 hover:bg-[#A8F23A]/5 dark:border-white/[0.07] dark:hover:bg-[#A8F23A]/5">
                                <input
                                    type="hidden"
                                    name="permissions[{{ $role }}][{{ $permission }}]"
                                    value="0"
                                >
                                <input
                                    type="checkbox"
                                    name="permissions[{{ $role }}][{{ $permission }}]"
                                    value="1"
                                    @checked($enabled)
                                    class="h-4 w-4 rounded border-gray-300 text-[#7cae00] focus:ring-[#A8F23A] dark:border-gray-600"
                                >
                                <span class="min-w-0">
                                    <span class="block text-xs font-semibold text-[#171719] dark:text-white">{{ $label }}</span>
                                    <span class="mt-0.5 block truncate text-[10px] text-black/35 dark:text-white/35">{{ $permission }}</span>
                                </span>
                                @if($override)
                                    <span class="ml-auto shrink-0 rounded-full bg-yellow-50 px-2 py-1 text-[9px] font-semibold text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300">override</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="sticky bottom-4 flex items-center justify-between gap-4 rounded-2xl border border-black/[0.06] bg-white/95 p-4 shadow-lg backdrop-blur dark:border-white/[0.08] dark:bg-gray-900/95">
                <p class="text-xs text-black/45 dark:text-white/45">Perubahan akses berlaku pada request berikutnya.</p>
                <button type="submit" class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:opacity-85">
                    Simpan Permission
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
