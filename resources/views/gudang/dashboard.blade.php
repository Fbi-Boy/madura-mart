<x-app-layout>
    <div class="space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Inventory operations
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Dashboard Gudang
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Pantau kondisi stok dan pergerakan barang tanpa angka fiktif.
                </p>
            </div>

            <div class="rounded-full border border-black/[0.06] bg-white px-3 py-1.5 text-xs text-black/55 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white/55">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['label' => 'Produk aktif', 'value' => number_format($activeProducts, 0, ',', '.'), 'meta' => 'SKU aktif di katalog'],
                ['label' => 'Unit stok', 'value' => number_format($totalStock, 0, ',', '.'), 'meta' => 'Total unit tersedia'],
                ['label' => 'Stok menipis', 'value' => number_format($lowStockCount, 0, ',', '.'), 'meta' => '1–10 unit tersisa'],
                ['label' => 'Stok habis', 'value' => number_format($outOfStockCount, 0, ',', '.'), 'meta' => 'Perlu segera diisi'],
            ] as $metric)
                <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">{{ $metric['label'] }}</p>
                    <p class="mt-2 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">{{ $metric['value'] }}</p>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">{{ $metric['meta'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pergerakan hari ini</h2>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">Barang masuk dan keluar dari transaksi yang sudah tercatat.</p>
                    </div>
                    <span class="rounded-xl bg-[#A8F23A]/15 px-2.5 py-1 text-[10px] font-bold text-[#4d6800] dark:text-[#A8F23A]">LIVE DATA</span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-[#A8F23A]/10 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#4d6800] dark:text-[#A8F23A]">Barang masuk</p>
                        <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($inboundToday, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">unit hari ini</p>
                    </div>
                    <div class="rounded-xl bg-black/[0.025] p-4 dark:bg-white/[0.035]">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/45 dark:text-white/45">Barang keluar</p>
                        <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($outboundToday, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">unit terjual hari ini</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Prioritas restock</h2>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Produk aktif dengan stok 10 unit atau kurang.</p>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse($restockProducts as $product)
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-black/[0.025] px-3 py-2.5 dark:bg-white/[0.035]">
                            <div class="min-w-0">
                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">{{ $product->name }}</p>
                                <p class="mt-0.5 text-[10px] text-black/35 dark:text-white/35">{{ $product->sku }}</p>
                            </div>
                            <span class="shrink-0 rounded-lg px-2 py-1 text-[10px] font-bold {{ $product->stock === 0 ? 'bg-red-500/10 text-red-600 dark:text-red-300' : 'bg-amber-500/10 text-amber-700 dark:text-amber-300' }}">
                                {{ $product->stock }} {{ $product->unit }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl bg-[#A8F23A]/10 p-4 text-xs text-[#4d6800] dark:text-[#A8F23A]">Tidak ada produk yang perlu restock.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Barang masuk terbaru</h2>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Penerimaan dari pembelian berstatus received.</p>

                <div class="mt-4 space-y-2">
                    @forelse($recentInbound as $item)
                        <div class="flex items-center justify-between gap-3 border-b border-black/[0.05] py-2.5 last:border-0 dark:border-white/[0.06]">
                            <div class="min-w-0">
                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">{{ $item->product?->name }}</p>
                                <p class="mt-0.5 text-[10px] text-black/35 dark:text-white/35">{{ $item->purchase?->invoice }} · {{ optional($item->purchase?->purchase_date)->format('d/m/Y') }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">+{{ number_format($item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-black/35 dark:text-white/35">Belum ada barang masuk.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Barang keluar terbaru</h2>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Pengeluaran stok dari transaksi penjualan paid.</p>

                <div class="mt-4 space-y-2">
                    @forelse($recentOutbound as $item)
                        <div class="flex items-center justify-between gap-3 border-b border-black/[0.05] py-2.5 last:border-0 dark:border-white/[0.06]">
                            <div class="min-w-0">
                                <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">{{ $item->product?->name }}</p>
                                <p class="mt-0.5 text-[10px] text-black/35 dark:text-white/35">{{ $item->sale?->invoice }} · {{ optional($item->sale?->sale_date)->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-semibold text-red-600 dark:text-red-300">-{{ number_format($item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-black/35 dark:text-white/35">Belum ada barang keluar.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="flex justify-end">
            <div class="flex flex-wrap justify-end gap-2">\n            <a href="{{ route('gudang.riwayat-stok.index') }}" class="rounded-xl border border-black/[0.06] bg-white px-4 py-2.5 text-xs font-semibold text-gray-800 hover:border-[#A8F23A] dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-white">Riwayat Stok</a>\n            <a href="{{ route('gudang.barang-keluar.index') }}" class="rounded-xl bg-[#A8F23A] px-4 py-2.5 text-xs font-semibold text-gray-900 hover:opacity-85">Riwayat Barang Keluar</a>\n        </div>
        </div>

    </div>
</x-app-layout>
