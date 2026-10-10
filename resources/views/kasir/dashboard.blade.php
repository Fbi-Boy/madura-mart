<x-app-layout>
<div class="space-y-5 font-poppins" x-data="{ clock: '' }" x-init="
    const tick = () => { const d = new Date(); clock = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); };
    tick(); setInterval(tick, 1000);
">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">

        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Point of Sale</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Dashboard Kasir</h1>
        </div>

        <div class="flex items-center gap-3">
            @if($openShift)
                <span class="inline-flex items-center gap-2 rounded-xl bg-lime-100 dark:bg-lime-900/30 px-3 py-1.5 text-xs text-lime-700 dark:text-lime-400">
                    <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></span>
                    Shift {{ $openShift->shift_number }} Aktif
                </span>
                <span class="font-mono text-sm font-bold text-[#171719] dark:text-white" x-text="clock"></span>
            @else
                <a href="{{ route('kasir.buka-shift') }}"
                   class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center transition">
                    Buka Shift
                </a>
            @endif
        </div>
    </div>

    {{-- ================= ALERT SHIFT BELUM DIBUKA ================= --}}
    @if(!$openShift)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Belum ada shift aktif</p>
                <p class="mt-0.5 text-xs text-amber-700/80 dark:text-amber-200/70">Buka shift terlebih dahulu sebelum membuat transaksi baru.</p>
            </div>
        </div>
    @endif

    {{-- ================= 4 STAT CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Omzet Hari Ini --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Omzet Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">{{ $todayTransactions }} transaksi paid</p>
        </div>

        {{-- Kas Shift --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Kas Shift</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp {{ number_format($expectedCash, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">
                {{ $openShift ? 'Opening + cash sales − refunds' : 'Tidak ada shift aktif' }}
            </p>
        </div>

        {{-- Cash --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Cash</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp {{ number_format($paymentSummary['cash'], 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Pembayaran tunai hari ini</p>
        </div>

        {{-- Non-Cash --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Non-Cash</p>
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">
                Rp {{ number_format($paymentSummary['transfer'] + $paymentSummary['qris'] + $paymentSummary['debit'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Transfer + QRIS + Debit</p>
        </div>
    </div>

    {{-- ================= WARNING REFUND ================= --}}
    @if($openShift && $shiftCashReturns > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m1 4 6 6-6 6"/><path d="M23 20V10a4 4 0 0 0-4-4H3"/>
            </svg>
            <div>
                <p class="text-xs font-semibold text-amber-800 dark:text-amber-200">Refund Cash Shift</p>
                <p class="mt-0.5 text-xs text-amber-700 dark:text-amber-200/80">
                    Rp {{ number_format($shiftCashReturns, 0, ',', '.') }} sudah dikurangkan dari kas yang diharapkan.
                </p>
            </div>
        </div>
    @endif

    {{-- ================= MAIN GRID: TRANSAKSI + PEMBAYARAN ================= --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">

        {{-- TRANSAKSI TERBARU --}}
        <section class="lg:col-span-8 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden flex flex-col">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Transaksi Terbaru</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Aktivitas penjualan kasir ini</p>
                </div>

                <div class="flex gap-2">
                    @if($openShift)
                        <a href="{{ route('kasir.transaksi-baru') }}"
                           class="h-9 px-3 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-medium flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 5v14"/><path d="M5 12h14"/>
                            </svg>
                            Transaksi Baru
                        </a>
                    @endif
                    <a href="{{ route('kasir.riwayat-transaksi') }}"
                       class="h-9 px-3 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-xs font-medium flex items-center transition">
                        Riwayat
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45 border-b border-black/5 dark:border-white/10">
                            <th class="px-5 py-3 font-medium">Invoice</th>
                            <th class="px-5 py-3 font-medium">Customer</th>
                            <th class="px-5 py-3 font-medium">Metode</th>
                            <th class="px-5 py-3 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-5 py-3">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $sale->invoice }}</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">{{ $sale->sale_date->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-3 text-xs text-black/60 dark:text-white/60">{{ $sale->customer?->name ?? 'Umum' }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $methodColor = match($sale->payment_method) {
                                            'cash' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                            'transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'qris' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                            'debit' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider {{ $methodColor }}">
                                        {{ $sale->payment_method }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>
                                    </svg>
                                    <p class="text-xs text-black/40 dark:text-white/40">Belum ada transaksi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- PEMBAYARAN HARI INI --}}
        <section class="lg:col-span-4 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 flex flex-col overflow-hidden">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pembayaran Hari Ini</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Distribusi omzet berdasarkan metode</p>
            </div>

            <div class="p-4 space-y-3 flex-1">
                @foreach([
                    ['label' => 'Cash', 'value' => $paymentSummary['cash'], 'color' => 'bg-lime-500'],
                    ['label' => 'QRIS', 'value' => $paymentSummary['qris'], 'color' => 'bg-purple-500'],
                    ['label' => 'Transfer', 'value' => $paymentSummary['transfer'], 'color' => 'bg-blue-500'],
                    ['label' => 'Debit / Kartu', 'value' => $paymentSummary['debit'], 'color' => 'bg-orange-500'],
                ] as $payment)
                    @php
                        $width = $todayRevenue > 0 ? min(100, ($payment['value'] / $todayRevenue) * 100) : 0;
                    @endphp
                    <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <span class="text-xs font-medium text-black/60 dark:text-white/60">{{ $payment['label'] }}</span>
                            <span class="text-xs font-bold text-[#171719] dark:text-white">
                                Rp {{ number_format($payment['value'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="h-1.5 rounded-full overflow-hidden bg-black/5 dark:bg-white/10">
                            <div class="h-full rounded-full {{ $payment['color'] }} transition-all duration-500" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- SHIFT STATUS --}}
            <div class="p-4 border-t border-black/5 dark:border-white/10">
                <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-3">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Status Shift</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white">
                        {{ $openShift ? 'Shift sedang berjalan' : 'Belum dibuka' }}
                    </p>
                    @if($openShift)
                        <a href="{{ route('kasir.tutup-shift') }}"
                           class="mt-2 inline-flex items-center gap-1 text-xs text-lime-700 dark:text-lime-400 hover:underline font-medium">
                            Kelola penutupan shift
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- ================= STOK KRITIS ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Stok Perlu Diperhatikan</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Produk aktif dengan stok 5 unit atau kurang</p>
            </div>
            <span class="text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-1 rounded font-medium"
                  x-text="'{{ $lowStockProducts->count() }} produk'"></span>
        </div>

        <div class="p-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @forelse($lowStockProducts as $product)
                <div class="rounded-xl border border-black/5 dark:border-white/10 p-3 hover:border-lime-400 transition">
                    <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">{{ $product->name }}</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">{{ $product->sku }}</p>
                    <div class="mt-3 flex items-baseline gap-1">
                        <span class="text-xl font-bold {{ $product->stock === 0 ? 'text-red-500' : 'text-amber-500' }}">{{ $product->stock }}</span>
                        <span class="text-[10px] font-medium text-black/45 dark:text-white/45">{{ $product->unit }}</span>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-5 rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <span class="text-xs font-medium text-lime-700 dark:text-lime-400">Semua stok aman — tidak ada produk di bawah 5 unit.</span>
                </div>
            @endforelse
        </div>
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
