<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Inventory Operations</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Dashboard Gudang</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pantau kondisi stok dan pergerakan barang secara real-time</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    {{-- ================= 5 STAT CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Produk Aktif --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Produk Aktif</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($activeProducts, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">SKU aktif di katalog</p>
        </div>

        {{-- Total Stok --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Stok</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($totalStock, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Total unit tersedia</p>
        </div>

        {{-- Stok Menipis --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Stok Menipis</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 9v4"/><path d="M12 17h.01"/>
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($lowStockCount, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">1–10 unit tersisa</p>
        </div>

        {{-- Stok Habis --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Stok Habis</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($outOfStockCount, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Perlu segera diisi</p>
        </div>

        {{-- Menunggu Penerimaan --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Menunggu Terima</p>
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"/>
                        <path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"/>
                        <path d="M12 12v9"/><path d="m8 16 4 4 4-4"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($pendingReceiving, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">PO siap diterima</p>
        </div>
    </div>

    {{-- ================= PERGERAKAN + RESTOCK ================= --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- PERGERAKAN HARI INI --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pergerakan Hari Ini</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Barang masuk & keluar dari transaksi tercatat</p>
                </div>
                <span class="rounded-xl bg-lime-100 dark:bg-lime-900/30 px-2.5 py-1 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                    LIVE
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>
                        </svg>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-lime-700 dark:text-lime-400">Barang Masuk</p>
                    </div>
                    <p class="text-2xl font-bold text-[#171719] dark:text-white">{{ number_format($inboundToday, 0, ',', '.') }}</p>
                    <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit hari ini</p>
                </div>

                <div class="rounded-xl bg-red-50 dark:bg-red-900/20 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                        </svg>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-red-700 dark:text-red-400">Barang Keluar</p>
                    </div>
                    <p class="text-2xl font-bold text-[#171719] dark:text-white">{{ number_format($outboundToday, 0, ',', '.') }}</p>
                    <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit terjual hari ini</p>
                </div>
            </div>
        </section>

        {{-- PRIORITAS RESTOCK --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Prioritas Restock</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Produk dengan stok 10 unit atau kurang</p>
            </div>

            <div class="space-y-2 max-h-[260px] overflow-y-auto pr-1">
                @forelse($restockProducts as $product)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 dark:bg-white/5 px-3 py-2.5">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">{{ $product->name }}</p>
                            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">{{ $product->sku }}</p>
                        </div>
                        <span class="shrink-0 rounded-lg px-2 py-1 text-[10px] font-bold
                            {{ $product->stock === 0
                                ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                    </div>
                @empty
                    <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        <span class="text-xs font-medium text-lime-700 dark:text-lime-400">Semua stok aman — tidak ada yang perlu restock.</span>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- ================= BARANG MASUK + KELUAR TERBARU ================= --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- BARANG MASUK --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Barang Masuk Terbaru</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Penerimaan dari pembelian berstatus received</p>
            </div>

            <div class="divide-y divide-black/5 dark:divide-white/5">
                @forelse($recentInbound as $item)
                    <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">{{ $item->product?->name }}</p>
                            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                {{ $item->purchase?->invoice }} · {{ optional($item->purchase?->purchase_date)->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="shrink-0 text-xs font-bold text-lime-600 dark:text-lime-400">
                            +{{ number_format($item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <svg class="w-10 h-10 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"/>
                            <path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"/>
                            <path d="M12 12v9"/><path d="m8 16 4 4 4-4"/>
                        </svg>
                        <p class="text-xs text-black/40 dark:text-white/40">Belum ada barang masuk.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- BARANG KELUAR --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Barang Keluar Terbaru</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pengeluaran stok dari transaksi penjualan</p>
            </div>

            <div class="divide-y divide-black/5 dark:divide-white/5">
                @forelse($recentOutbound as $item)
                    <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">{{ $item->product?->name }}</p>
                            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                {{ $item->sale?->invoice }} · {{ optional($item->sale?->sale_date)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <span class="shrink-0 text-xs font-bold text-red-600 dark:text-red-400">
                            −{{ number_format($item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <svg class="w-10 h-10 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                        </svg>
                        <p class="text-xs text-black/40 dark:text-white/40">Belum ada barang keluar.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- ================= AKSES GUDANG ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
        <div class="mb-4">
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Akses Cepat Gudang</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Jalur kerja utama untuk operasional gudang</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

            <a href="{{ route('gudang.penerimaan.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"/>
                        <path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"/>
                        <path d="M12 12v9"/><path d="m8 16 4 4 4-4"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-[#171719] dark:text-white">Penerimaan Barang</p>
                <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Terima kiriman dari supplier</p>
            </a>

            <a href="{{ route('gudang.stock-opname.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-[#171719] dark:text-white">Stock Opname</p>
                <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Hitung & cocokkan stok fisik</p>
            </a>

            <a href="{{ route('gudang.barang-keluar.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-[#171719] dark:text-white">Barang Keluar</p>
                <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Catat pengeluaran stok</p>
            </a>

            <a href="{{ route('gudang.riwayat-stok.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-[#171719] dark:text-white">Riwayat Stok</p>
                <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Log semua pergerakan</p>
            </a>
        </div>
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
