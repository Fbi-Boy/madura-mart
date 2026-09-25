<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">Inventory operations</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">Barang Keluar</h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">Riwayat pengeluaran stok berdasarkan transaksi penjualan yang sudah paid.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-full border border-black/[0.06] bg-white px-4 py-2 text-xs font-semibold text-black/60 hover:bg-black/[0.02] dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/60">Kembali ke Dashboard</a>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Unit keluar hari ini</p>
                <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($todayUnits, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Transaksi hari ini</p>
                <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($todayTransactions, 0, ',', '.') }}</p>
            </div>
        </div>

        <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
            <form method="GET" class="grid gap-3 md:grid-cols-[1fr_180px_auto]">
                <input name="search" value="{{ request('search') }}" placeholder="Cari produk, SKU, atau invoice..." class="rounded-xl border-black/[0.08] text-sm dark:border-white/[0.1] dark:bg-white/[0.04] dark:text-white">
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-xl border-black/[0.08] text-sm dark:border-white/[0.1] dark:bg-white/[0.04] dark:text-white">
                <button class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 hover:opacity-85">Filter</button>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-black/[0.06] bg-white dark:border-white/[0.08] dark:bg-white/[0.04]">
            <div class="border-b border-black/[0.05] px-5 py-4 dark:border-white/[0.06]">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Pengeluaran</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-black/[0.02] text-xs text-black/45 dark:bg-white/[0.025] dark:text-white/45">
                        <tr>
                            <th class="px-5 py-3 font-medium">Produk</th>
                            <th class="px-5 py-3 font-medium">Invoice</th>
                            <th class="px-5 py-3 font-medium">Tanggal</th>
                            <th class="px-5 py-3 text-right font-medium">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                        @forelse($outboundItems as $item)
                            <tr>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-[#171719] dark:text-white">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</p>
                                    <p class="text-xs text-black/35 dark:text-white/35">{{ $item->product?->sku }}</p>
                                </td>
                                <td class="px-5 py-3 text-black/60 dark:text-white/60">{{ $item->sale?->invoice ?? '-' }}</td>
                                <td class="px-5 py-3 text-black/60 dark:text-white/60">{{ optional($item->sale?->sale_date)->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-red-600 dark:text-red-300">-{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->product?->unit }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-12 text-center text-sm text-black/35 dark:text-white/35">Belum ada barang keluar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-black/[0.05] px-5 py-4 dark:border-white/[0.06]">
                {{ $outboundItems->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
