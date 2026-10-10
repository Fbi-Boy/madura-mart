<x-app-layout>
<div class="space-y-5 font-poppins">

    @php
        $isPurchasing = auth()->user()->role === 'purchasing';
        $indexRoute = $isPurchasing ? 'purchasing.purchases.index' : 'admin.purchases.index';
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Transaksi</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Detail Pembelian</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45 font-mono">{{ $purchase->invoice }}</p>
        </div>

        <a href="{{ route($indexRoute) }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- INFO CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Supplier</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white truncate">{{ $purchase->supplier?->name ?? '-' }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white font-mono">{{ $purchase->purchase_date?->format('d/m/Y') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total</p>
            <p class="mt-1 text-sm font-bold text-lime-600 dark:text-lime-400">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Status</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white capitalize">{{ $purchase->status }}</p>
        </div>
    </div>

    {{-- ITEMS --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Item Pembelian</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $purchase->items->count() }} item</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Produk</th>
                        <th class="px-5 py-3.5 text-center font-medium">Qty</th>
                        <th class="px-5 py-3.5 text-right font-medium">Harga</th>
                        <th class="px-5 py-3.5 text-right font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($purchase->items as $item)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white">{{ $item->product?->name ?? '-' }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 font-mono mt-0.5">{{ $item->product?->sku }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[11px] font-bold text-[#171719] dark:text-white">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right text-xs text-black/70 dark:text-white/70">
                                Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-bold text-[#171719] dark:text-white">
                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-12 text-center text-xs text-black/40 dark:text-white/40">Tidak ada item.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t-2 border-black/5 dark:border-white/10">
                    <tr>
                        <td colspan="3" class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-black/45 dark:text-white/45">Total</td>
                        <td class="px-5 py-4 text-right text-base font-bold text-lime-600 dark:text-lime-400">
                            Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
