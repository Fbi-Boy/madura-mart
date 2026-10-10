<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Inventory Operations</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Barang Keluar</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                Riwayat pengeluaran stok dari transaksi penjualan yang sudah paid
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $outboundItems->total() }} catatan
            </span>
            <a href="{{ route('dashboard') }}"
               class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        {{-- UNIT KELUAR HARI INI --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Unit Keluar Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#171719] dark:text-white">{{ number_format($todayUnits, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit terjual hari ini</p>
        </div>

        {{-- TRANSAKSI HARI INI --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Transaksi Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#171719] dark:text-white">{{ number_format($todayTransactions, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">transaksi paid</p>
        </div>
    </div>

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk, SKU, atau invoice..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- DATE --}}
            <input type="date" name="date" value="{{ request('date') }}"
                class="h-10 px-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

            {{-- SUBMIT --}}
            <button type="submit"
                class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                </svg>
                Filter
            </button>

            {{-- RESET --}}
            @if(request()->anyFilled(['search', 'date']))
                <a href="{{ route('gudang.barang-keluar.index') }}"
                   class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- ================= TABLE ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Pengeluaran</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    {{ $outboundItems->total() }} catatan barang keluar
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Produk</th>
                        <th class="px-5 py-3.5 font-medium">Invoice</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 text-right font-medium">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($outboundItems as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- PRODUK --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">
                                            {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                        </p>
                                        <p class="text-[10px] text-black/45 dark:text-white/45 font-mono mt-0.5">
                                            {{ $item->product?->sku ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- INVOICE --}}
                            <td class="px-5 py-4">
                                @if($item->sale?->invoice)
                                    <span class="inline-flex px-2 py-1 rounded-md bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold text-lime-700 dark:text-lime-400 font-mono">
                                        {{ $item->sale->invoice }}
                                    </span>
                                @else
                                    <span class="text-xs text-black/40 dark:text-white/40">—</span>
                                @endif
                            </td>

                            {{-- TANGGAL --}}
                            <td class="px-5 py-4">
                                @if($item->sale?->sale_date)
                                    <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                        {{ $item->sale->sale_date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                        {{ $item->sale->sale_date->format('H:i') }}
                                    </p>
                                @else
                                    <span class="text-xs text-black/40 dark:text-white/40">—</span>
                                @endif
                            </td>

                            {{-- JUMLAH --}}
                            <td class="px-5 py-4 text-right">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-100 dark:bg-red-900/30 text-[11px] font-bold text-red-600 dark:text-red-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                                    </svg>
                                    {{ number_format($item->quantity, 0, ',', '.') }}
                                    <span class="opacity-70">{{ $item->product?->unit }}</span>
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada barang keluar</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">
                                    Barang keluar akan tercatat otomatis dari transaksi penjualan.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($outboundItems->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $outboundItems->links() }}
            </div>
        @endif
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
