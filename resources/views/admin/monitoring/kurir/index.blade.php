<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Monitoring</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Monitoring Kurir</h2>
        <p class="mt-1 text-sm text-black/40 dark:text-white/40">Pantau kurir, kendaraan, dan status operasional.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <p class="text-sm text-black/45 dark:text-white/45">Total Kurir</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ $totalCouriers }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <p class="text-sm text-black/45 dark:text-white/45">Kurir Aktif</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ $activeCouriers }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <p class="text-sm text-black/45 dark:text-white/45">Kurir Nonaktif</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ $inactiveCouriers }}</p>
        </div>
    </div>

    <form method="GET" class="flex gap-3 rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
        <input
            name="search"
            value="{{ $search }}"
            placeholder="Cari kode, nama, telepon, atau kendaraan..."
            class="w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm text-[#171719] outline-none focus:border-black/30 dark:border-white/10 dark:text-white"
        >
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-black/5 text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                    <tr>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Kurir</th>
                        <th class="px-5 py-4">Telepon</th>
                        <th class="px-5 py-4">Kendaraan</th>
                        <th class="px-5 py-4">Nomor Kendaraan</th>
                        <th class="px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                @forelse($couriers as $courier)
                    <tr>
                        <td class="px-5 py-4 font-medium text-[#171719] dark:text-white">{{ $courier->code }}</td>
                        <td class="px-5 py-4 text-[#171719] dark:text-white">{{ $courier->name }}</td>
                        <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $courier->phone ?: '-' }}</td>
                        <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $courier->vehicle_type ?: '-' }}</td>
                        <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $courier->vehicle_number ?: '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $courier->is_active ? 'bg-green-50 text-green-700 dark:bg-green-400/10 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-white/60' }}">
                                {{ $courier->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-black/40 dark:text-white/40">Belum ada data kurir.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($couriers->hasPages())
            <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $couriers->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
