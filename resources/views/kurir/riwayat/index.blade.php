<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kurir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Riwayat Pengiriman</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Semua pengiriman yang sudah selesai atau gagal</p>
        </div>

        <a href="{{ route('kurir.pengiriman.index') }}"
           class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Tugas Aktif
        </a>
    </div>

    {{-- TABLE --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Lengkap</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    {{ ($orders ?? collect())->total() ?? 0 }} pengiriman
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Pesanan</th>
                        <th class="px-5 py-3.5 font-medium">Customer</th>
                        <th class="px-5 py-3.5 font-medium">Alamat</th>
                        <th class="px-5 py-3.5 font-medium">Status</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse (($orders ?? collect()) as $order)
                        @php
                            $statusColor = match($order->status) {
                                'delivered' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'failed'    => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default     => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $order->customer?->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 truncate max-w-[240px]">{{ $order->delivery_address }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $order->updated_at?->format('d/m/Y H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada riwayat pengiriman</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Riwayat akan muncul setelah pengiriman selesai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($orders ?? collect())->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
