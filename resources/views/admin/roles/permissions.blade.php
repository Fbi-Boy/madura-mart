<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Role & Permission</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Matriks akses yang sedang diterapkan sistem untuk setiap role.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex w-fit rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Manajemen User</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Akses berbasis konfigurasi</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Matriks ini bersumber langsung dari <code class="rounded bg-white/70 px-1 dark:bg-gray-900/60">config/permissions.php</code>.
                    Perubahan akses harus dilakukan melalui perubahan kode yang direview, sehingga tidak ada perubahan permission diam-diam dari UI.
                </p>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
                            <tr>
                                <th class="sticky left-0 z-10 bg-gray-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">Permission</th>
                                @foreach ($roles as $role)
                                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $role }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($permissions as $permission => $label)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="sticky left-0 bg-white px-5 py-4 dark:bg-gray-800">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $label }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $permission }}</p>
                                    </td>
                                    @foreach ($roles as $role)
                                        @php($allowed = in_array($permission, $rolePermissions[$role] ?? [], true))
                                        <td class="px-4 py-4 text-center">
                                            @if ($allowed)
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#A8F23A]/25 text-sm font-bold text-gray-900 dark:text-[#A8F23A]" aria-label="{{ $role }} memiliki {{ $permission }}">✓</span>
                                            @else
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-sm text-gray-300 dark:bg-gray-700 dark:text-gray-500" aria-label="{{ $role }} tidak memiliki {{ $permission }}">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
