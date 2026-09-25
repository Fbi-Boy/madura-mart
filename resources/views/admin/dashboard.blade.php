<x-app-layout>
    <div class="space-y-6">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Business overview
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Dashboard Admin
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Ringkasan operasional Madura Mart hari ini.
                </p>
            </div>

            <div class="rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs text-black/55
                        dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['label' => 'Omzet bulan ini', 'value' => 'Rp '.number_format($monthlyRevenue, 0, ',', '.'), 'meta' => 'Transaksi berstatus paid', 'icon' => 'Rp'],
                ['label' => 'Transaksi hari ini', 'value' => number_format($todayTransactions, 0, ',', '.'), 'meta' => 'Penjualan paid', 'icon' => '#'],
                ['label' => 'Produk aktif', 'value' => number_format($activeProducts, 0, ',', '.'), 'meta' => $lowStockProducts.' produk perlu perhatian stok', 'icon' => 'P'],
                ['label' => 'Pesanan berjalan', 'value' => number_format($pendingOrders, 0, ',', '.'), 'meta' => $pendingPurchases.' pembelian masih draft', 'icon' => 'O'],
            ] as $card)
                <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)]
                            dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#A8F23A]/20
                                    text-sm font-bold text-[#365500] dark:text-[#A8F23A]">
                            {{ $card['icon'] }}
                        </div>
                        <span class="rounded-full bg-black/[0.04] px-2.5 py-1 text-[10px] font-medium text-black/45
                                     dark:bg-white/[0.06] dark:text-white/45">
                            live data
                        </span>
                    </div>
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">
                        {{ $card['label'] }}
                    </p>
                    <p class="mt-1 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">
                        {{ $card['value'] }}
                    </p>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">
                        {{ $card['meta'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.35fr_.65fr]">

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5
                            dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Penjualan terbaru</h2>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">Transaksi paid paling baru.</p>
                    </div>
                    <a href="{{ route('admin.monitoring.penjualan') }}"
                       class="text-xs font-semibold text-[#4d6800] hover:underline dark:text-[#A8F23A]">
                        Lihat semua
                    </a>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full min-w-[620px] text-left">
                        <thead>
                            <tr class="border-b border-black/[0.06] text-[10px] uppercase tracking-[0.08em] text-black/35 dark:border-white/[0.08] dark:text-white/35">
                                <th class="pb-3 font-semibold">Invoice</th>
                                <th class="pb-3 font-semibold">Customer</th>
                                <th class="pb-3 font-semibold">Pembayaran</th>
                                <th class="pb-3 text-right font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="py-3 text-xs font-semibold text-[#171719] dark:text-white">
                                        {{ $sale->invoice }}
                                        <span class="block mt-0.5 font-normal text-black/35 dark:text-white/35">
                                            {{ $sale->sale_date->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-xs text-black/55 dark:text-white/55">
                                        {{ $sale->customer?->name ?? 'Umum' }}
                                    </td>
                                    <td class="py-3 text-xs capitalize text-black/55 dark:text-white/55">
                                        {{ $sale->payment_method }}
                                    </td>
                                    <td class="py-3 text-right text-xs font-semibold text-[#171719] dark:text-white">
                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-xs text-black/35 dark:text-white/35">
                                        Belum ada transaksi penjualan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5
                            dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Perhatian stok</h2>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Produk aktif dengan stok ≤ 10.</p>
                </div>

                <div class="mt-5 space-y-2">
                    @forelse($stockAlerts as $product)
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-black/[0.025] px-3 py-2.5
                                    dark:bg-white/[0.035]">
                            <div class="min-w-0">
                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">{{ $product->name }}</p>
                                <p class="mt-0.5 text-[10px] text-black/35 dark:text-white/35">{{ $product->sku }}</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2 py-1 text-[10px] font-semibold
                                         {{ $product->stock === 0 ? 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' }}">
                                {{ $product->stock }} {{ $product->unit }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl bg-[#A8F23A]/10 p-4 text-xs text-[#4d6800] dark:text-[#A8F23A]">
                            Tidak ada stok kritis saat ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Produk terlaris bulan ini</h2>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">Berdasarkan jumlah unit terjual.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($topProducts as $item)
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#A8F23A]/20 text-xs font-bold text-[#365500] dark:text-[#A8F23A]">
                                {{ $loop->iteration }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">
                                    {{ $item->product?->name ?? 'Produk dihapus' }}
                                </p>
                                <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-black/[0.05] dark:bg-white/[0.08]">
                                    <div class="h-full rounded-full bg-[#A8F23A]"
                                         style="width: {{ max(8, min(100, ($item->quantity_sold / max(1, (int) $topProducts->max('quantity_sold'))) * 100)) }}%"></div>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-black/55 dark:text-white/55">
                                {{ $item->quantity_sold }} unit
                            </span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-black/35 dark:text-white/35">
                            Belum ada data penjualan produk bulan ini.
                        </p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Ringkasan operasional</h2>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Data pendukung aktivitas bisnis.</p>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-2.5">
                    <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                        <p class="text-[10px] uppercase tracking-[0.07em] text-black/35 dark:text-white/35">Customer aktif</p>
                        <p class="mt-1 text-xl font-semibold text-[#171719] dark:text-white">{{ number_format($activeCustomers) }}</p>
                    </div>
                    <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                        <p class="text-[10px] uppercase tracking-[0.07em] text-black/35 dark:text-white/35">Kurir aktif</p>
                        <p class="mt-1 text-xl font-semibold text-[#171719] dark:text-white">{{ number_format($activeCouriers) }}</p>
                    </div>
                    <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                        <p class="text-[10px] uppercase tracking-[0.07em] text-black/35 dark:text-white/35">Pesanan aktif</p>
                        <p class="mt-1 text-xl font-semibold text-[#171719] dark:text-white">{{ number_format($pendingOrders) }}</p>
                    </div>
                    <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                        <p class="text-[10px] uppercase tracking-[0.07em] text-black/35 dark:text-white/35">Pembelian draft</p>
                        <p class="mt-1 text-xl font-semibold text-[#171719] dark:text-white">{{ number_format($pendingPurchases) }}</p>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-3 text-xs text-[#4d6800] dark:text-[#A8F23A]">
                    Dashboard admin menggunakan data database secara langsung agar informasi operasional tetap aktual.
                </div>
            </section>
        </div>

    </div>
</x-app-layout>
